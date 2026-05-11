<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class FacebookImportService
{
    protected $pageId;
    protected $accessToken;
    protected $graphVersion = 'v22.0';

    public function __construct()
    {
        $this->pageId = config('services.facebook.page_id');
        $this->accessToken = config('services.facebook.access_token');
    }

    /**
     * Import Facebook posts to blog
     */
    public function import($limit = 10)
    {
        return $this->importPosts($this->fetchLatestPosts($limit));
    }

    /**
     * Import Facebook posts published on or after the given date.
     */
    public function importSince($since, $pageSize = 25)
    {
        $sinceDate = $this->normalizeSinceDate($since);

        return $this->importPosts($this->fetchPostSummariesSince($sinceDate, $pageSize));
    }

    /**
     * Fetch posts from the configured Facebook page.
     */
    public function fetchPosts($limit = 10)
    {
        return $this->fetchLatestPosts($limit);
    }

    /**
     * Import a prepared list of Facebook posts while avoiding duplicates.
     */
    protected function importPosts(array $posts)
    {
        $stats = [
            'imported' => 0,
            'skipped' => 0,
            'errors' => 0,
            'error_messages' => [],
        ];

        try {
            $posts = $this->deduplicatePosts($posts);

            if (empty($posts)) {
                return $stats;
            }

            $existingIds = BlogPost::whereIn('facebook_post_id', collect($posts)->pluck('id')->all())
                ->pluck('facebook_post_id')
                ->flip();

            foreach ($posts as $post) {
                try {
                    if ($existingIds->has($post['id'])) {
                        $stats['skipped']++;
                        continue;
                    }

                    $fullPost = $this->requiresPostHydration($post)
                        ? $this->fetchPostById($post['id'])
                        : $post;

                    $this->convertToBlog($fullPost);
                    $stats['imported']++;
                } catch (Exception $e) {
                    $stats['errors']++;
                    $stats['error_messages'][] = "Post {$post['id']}: " . $e->getMessage();
                    \Log::error('Facebook import error for post: ' . $post['id'], ['error' => $e->getMessage()]);
                }
            }
        } catch (Exception $e) {
            $stats['errors']++;
            $stats['error_messages'][] = $e->getMessage();
            \Log::error('Facebook import error', ['error' => $e->getMessage()]);
        }

        return $stats;
    }

    /**
     * Fetch Facebook events for the configured page.
     */
    public function fetchEvents($limit = 3)
    {
        return $this->normalizeEvents($this->fetchRawEvents(max($limit * 3, 10)), $limit);
    }

    /**
     * Import Facebook events for the selected years into the local events table.
     */
    public function importEventsForYears(array $years)
    {
        $stats = [
            'imported' => 0,
            'updated' => 0,
            'errors' => 0,
            'error_messages' => [],
        ];

        $years = collect($years)
            ->map(fn ($year) => (int) $year)
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        try {
            $events = $this->filterEventsByYears($this->fetchRawEvents(100), $years);

            if (empty($events)) {
                throw new Exception('No Facebook events found for the selected years');
            }

            foreach ($events as $facebookEvent) {
                try {
                    $existingEvent = Event::where('facebook_event_id', $facebookEvent['id'])->first();

                    $attributes = $this->buildEventData($facebookEvent, $existingEvent);

                    if ($existingEvent) {
                        $existingEvent->fill($attributes);
                        $existingEvent->save();
                        $stats['updated']++;
                    } else {
                        Event::create($attributes);
                        $stats['imported']++;
                    }
                } catch (Exception $e) {
                    $stats['errors']++;
                    $stats['error_messages'][] = "Event {$facebookEvent['id']}: " . $e->getMessage();
                    \Log::error('Facebook event import error', [
                        'facebook_event_id' => $facebookEvent['id'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (Exception $e) {
            $stats['errors']++;
            $stats['error_messages'][] = $e->getMessage();
            \Log::error('Facebook events import failed', ['error' => $e->getMessage()]);
        }

        return $stats;
    }

    /**
     * Refresh an existing imported blog post with live Facebook data.
     */
    public function syncImportedBlogPost(BlogPost $blogPost)
    {
        $facebookPostId = $blogPost->facebook_post_id ?: $this->inferFacebookPostIdFromBlogPost($blogPost);

        if (empty($facebookPostId)) {
            throw new Exception("Unable to determine the Facebook post ID for blog post #{$blogPost->id}");
        }

        $facebookPost = $this->fetchPostById($facebookPostId);
        $attributes = $this->buildBlogPostData($facebookPost, $blogPost);

        $attributes['author_id'] = $blogPost->author_id ?: $this->resolveAdminUser()->id;
        $attributes['category'] = $blogPost->category ?: 'Facebook';
        $attributes['tags'] = !empty($blogPost->tags) ? $blogPost->tags : ['Facebook', 'Import', 'Social Media'];

        $blogPost->fill($attributes);
        $blogPost->save();

        return $blogPost;
    }

    /**
     * Fetch latest posts from Facebook
     */
    protected function fetchLatestPosts($limit = 10)
    {
        if (empty($this->accessToken)) {
            throw new Exception('Facebook credentials not configured. Please set FACEBOOK_ACCESS_TOKEN in .env');
        }

        $errors = [];

        foreach ($this->getPostTargets() as $target) {
            $response = Http::timeout(30)->get($this->graphUrl("{$target}/posts"), [
                'fields' => $this->getPostFields(),
                'limit' => $limit,
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                return $response->json('data', []);
            }

            $error = $response->json('error.message', 'Unknown error');
            $errors[] = "{$target}/posts: {$error}";

            \Log::warning('Facebook posts fetch failed', [
                'target' => $target,
                'status' => $response->status(),
                'error' => $error,
            ]);
        }

        throw new Exception('Facebook API Error: ' . implode(' | ', $errors));
    }

    /**
     * Fetch light Facebook post summaries published on or after the given date.
     */
    protected function fetchPostSummariesSince(Carbon $sinceDate, $pageSize = 25)
    {
        if (empty($this->accessToken)) {
            throw new Exception('Facebook credentials not configured. Please set FACEBOOK_ACCESS_TOKEN in .env');
        }

        $errors = [];

        foreach ($this->getPostTargets() as $target) {
            $params = [
                'fields' => $this->getPostSummaryFields(),
                'limit' => $pageSize,
                'since' => $sinceDate->startOfDay()->timestamp,
                'access_token' => $this->accessToken,
            ];

            $response = Http::timeout(30)->get($this->graphUrl("{$target}/posts"), $params);

            if ($response->successful()) {
                return $this->collectPagedPosts($response);
            }

            $error = $response->json('error.message', 'Unknown error');
            $errors[] = "{$target}/posts: {$error}";

            \Log::warning('Facebook posts fetch failed', [
                'target' => $target,
                'status' => $response->status(),
                'error' => $error,
                'since' => $sinceDate->toDateString(),
            ]);
        }

        throw new Exception('Facebook API Error: ' . implode(' | ', $errors));
    }

    /**
     * Fetch raw Facebook events before year filtering or UI formatting.
     */
    protected function fetchRawEvents($limit = 100)
    {
        if (empty($this->accessToken)) {
            throw new Exception('Facebook credentials not configured. Please set FACEBOOK_ACCESS_TOKEN in .env');
        }

        $errors = [];

        foreach ($this->getEventTargets() as $target) {
            $response = Http::timeout(30)->get($this->graphUrl("{$target}/events"), [
                'fields' => $this->getEventFields(),
                'limit' => $limit,
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                return $response->json('data', []);
            }

            $error = $response->json('error.message', 'Unknown error');
            $errors[] = "{$target}/events: {$error}";

            \Log::warning('Facebook events fetch failed', [
                'target' => $target,
                'status' => $response->status(),
                'error' => $error,
            ]);
        }

        throw new Exception('Facebook API Error: ' . implode(' | ', $errors));
    }

    /**
     * Fetch one specific Facebook post by its canonical ID.
     */
    protected function fetchPostById($postId)
    {
        $response = Http::timeout(30)->get($this->graphUrl($postId), [
            'fields' => $this->getPostFields(),
            'access_token' => $this->accessToken,
        ]);

        if (!$response->successful()) {
            $error = $response->json('error.message', 'Unknown error');
            throw new Exception("Facebook API Error: {$error}");
        }

        return $response->json();
    }

    /**
     * Build the list of Graph API targets to try for page posts.
     */
    protected function getPostTargets()
    {
        $targets = [];

        if (!empty($this->pageId)) {
            $targets[] = $this->pageId;
        }

        // A page access token can resolve its own page via /me, which lets us
        // recover gracefully when FACEBOOK_PAGE_ID is misconfigured.
        $targets[] = 'me';

        return array_values(array_unique($targets));
    }

    /**
     * Build the list of Graph API targets to try for page events.
     */
    protected function getEventTargets()
    {
        return $this->getPostTargets();
    }

    /**
     * Graph fields required for imports and shared post resolution.
     */
    protected function getPostFields()
    {
        return 'id,message,story,created_time,full_picture,permalink_url,attachments{title,description,type,url,target,media{image,source},subattachments{media{image,source},target,type,url}}';
    }

    /**
     * Graph fields used to discover post ids for broad since-date imports.
     */
    protected function getPostSummaryFields()
    {
        return 'id,created_time';
    }

    /**
     * Graph fields required for Facebook events.
     */
    protected function getEventFields()
    {
        return 'id,name,description,start_time,end_time,place,cover,attending_count,interested_count,ticket_uri';
    }

    /**
     * Generate a Graph API URL for the current version.
     */
    protected function graphUrl($path)
    {
        return "https://graph.facebook.com/{$this->graphVersion}/{$path}";
    }

    /**
     * Follow Graph pagination links and return all fetched posts.
     */
    protected function collectPagedPosts($initialResponse, $maxPages = 25)
    {
        $posts = [];
        $response = $initialResponse;
        $page = 0;

        while (true) {
            $posts = array_merge($posts, $response->json('data', []));
            $nextUrl = $response->json('paging.next');
            $page++;

            if (empty($nextUrl) || $page >= $maxPages) {
                break;
            }

            $response = Http::timeout(30)->get($nextUrl);

            if (!$response->successful()) {
                $error = $response->json('error.message', 'Unknown error');
                throw new Exception("Facebook API Error: {$error}");
            }
        }

        return $this->deduplicatePosts($posts);
    }

    /**
     * Keep only one entry per Facebook post id.
     */
    protected function deduplicatePosts(array $posts)
    {
        return collect($posts)
            ->filter(fn ($post) => !empty($post['id']))
            ->unique('id')
            ->values()
            ->all();
    }

    /**
     * Normalize the since date used for scoped imports.
     */
    protected function normalizeSinceDate($since)
    {
        return Carbon::parse($since)->startOfDay();
    }

    /**
     * Determine whether the post payload still needs a detailed fetch.
     */
    protected function requiresPostHydration(array $post)
    {
        return !array_key_exists('attachments', $post)
            && !array_key_exists('message', $post)
            && !array_key_exists('story', $post)
            && !array_key_exists('full_picture', $post);
    }

    /**
     * Deduplicate and order Facebook events for display.
     */
    protected function normalizeEvents(array $events, $limit)
    {
        $threshold = now()->subDay();
        $items = $this->deduplicateEvents($events);

        $upcoming = $items
            ->filter(fn ($event) => Carbon::parse($event['start_time'])->greaterThanOrEqualTo($threshold))
            ->sortBy(fn ($event) => Carbon::parse($event['start_time'])->timestamp);

        $past = $items
            ->reject(fn ($event) => Carbon::parse($event['start_time'])->greaterThanOrEqualTo($threshold))
            ->sortByDesc(fn ($event) => Carbon::parse($event['start_time'])->timestamp);

        return $upcoming
            ->concat($past)
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * Keep only the best version of duplicated Facebook events.
     */
    protected function deduplicateEvents(array $events)
    {
        return collect($events)
            ->filter(fn ($event) => !empty($event['id']) && !empty($event['name']) && !empty($event['start_time']))
            ->groupBy(fn ($event) => Str::slug($event['name']) . '|' . ($event['start_time'] ?? ''))
            ->map(function ($group) {
                return $group->sortByDesc(function ($event) {
                    return (!empty($event['cover']['source']) ? 1000000 : 0)
                        + (($event['interested_count'] ?? 0) * 10)
                        + ($event['attending_count'] ?? 0);
                })->first();
            })
            ->values();
    }

    /**
     * Filter events by year after deduplication.
     */
    protected function filterEventsByYears(array $events, array $years)
    {
        return $this->deduplicateEvents($events)
            ->filter(fn ($event) => in_array(Carbon::parse($event['start_time'])->year, $years, true))
            ->sortByDesc(fn ($event) => Carbon::parse($event['start_time'])->timestamp)
            ->values()
            ->all();
    }

    /**
     * Build the local Event payload from a Facebook event.
     */
    protected function buildEventData(array $facebookEvent, ?Event $existingEvent = null)
    {
        $startsAt = !empty($facebookEvent['start_time']) ? Carbon::parse($facebookEvent['start_time']) : null;
        $endsAt = !empty($facebookEvent['end_time']) ? Carbon::parse($facebookEvent['end_time']) : null;
        $imagePath = $existingEvent?->image;
        $coverImageUrl = $facebookEvent['cover']['source'] ?? null;

        if (!empty($coverImageUrl)) {
            $downloadedImage = $this->downloadEventImage($coverImageUrl, $facebookEvent['id']);

            if (!empty($downloadedImage)) {
                $imagePath = $downloadedImage;
            }
        }

        return [
            'facebook_event_id' => $facebookEvent['id'],
            'type' => $existingEvent?->type ?: $this->inferEventType($facebookEvent),
            'title' => $facebookEvent['name'] ?? 'Événement Facebook',
            'description' => $this->buildEventDescription($facebookEvent),
            'image' => $imagePath,
            'icon' => $existingEvent?->icon,
            'icon_color' => $existingEvent?->icon_color ?: 'blue',
            'link' => $this->extractEventLink($facebookEvent),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'location_name' => $this->extractEventLocation($facebookEvent),
            'order' => $existingEvent?->order ?? 0,
            'is_featured' => $existingEvent?->is_featured ?? false,
            'is_active' => $existingEvent?->is_active ?? true,
            'show_on_website' => $existingEvent?->show_on_website ?? true,
        ];
    }

    /**
     * Infer a local event type from Facebook event text.
     */
    protected function inferEventType(array $facebookEvent)
    {
        $haystack = Str::lower(($facebookEvent['name'] ?? '') . ' ' . ($facebookEvent['description'] ?? ''));

        if (Str::contains($haystack, ['assemblée', 'assemblee', 'assembly', 'agp'])) {
            return 'assembly';
        }

        if (Str::contains($haystack, ['forum'])) {
            return 'forum-session';
        }

        if (Str::contains($haystack, ['formation', 'training', 'bootcamp', 'atelier', 'workshop', 'session officielle', 'vision lab', 'project management'])) {
            return 'training-session';
        }

        if (Str::contains($haystack, ['réunion', 'reunion', 'meeting', 'team building', 'passation'])) {
            return 'meeting';
        }

        if (Str::contains($haystack, ['projet', 'project', 'initiative', 'campaign', 'campagne'])) {
            return 'project';
        }

        return 'development';
    }

    /**
     * Build a useful local description for imported Facebook events.
     */
    protected function buildEventDescription(array $facebookEvent)
    {
        $description = trim((string) ($facebookEvent['description'] ?? ''));

        if ($description !== '') {
            return $description;
        }

        $parts = [$facebookEvent['name'] ?? 'Événement Facebook'];
        $location = $this->extractEventLocation($facebookEvent);

        if (!empty($facebookEvent['start_time'])) {
            $parts[] = 'Date : ' . Carbon::parse($facebookEvent['start_time'])->format('d/m/Y H:i');
        }

        if (!empty($location)) {
            $parts[] = 'Lieu : ' . $location;
        }

        return implode("\n", $parts);
    }

    /**
     * Extract the best public link for a Facebook event.
     */
    protected function extractEventLink(array $facebookEvent)
    {
        return $facebookEvent['ticket_uri'] ?? 'https://www.facebook.com/events/' . $facebookEvent['id'];
    }

    /**
     * Extract a readable location from a Facebook event payload.
     */
    protected function extractEventLocation(array $facebookEvent)
    {
        $place = trim((string) ($facebookEvent['place']['name'] ?? ''));

        if ($place !== '') {
            return $place;
        }

        $location = $facebookEvent['place']['location'] ?? [];
        $parts = array_filter([
            $location['street'] ?? null,
            $location['city'] ?? null,
            $location['country'] ?? null,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Convert Facebook post to BlogPost
     */
    protected function convertToBlog($facebookPost)
    {
        $adminUser = $this->resolveAdminUser();

        // Create blog post
        BlogPost::create(array_merge($this->buildBlogPostData($facebookPost), [
            'author_id' => $adminUser->id,
            'category' => 'Facebook',
            'tags' => ['Facebook', 'Import', 'Social Media'],
            'is_published' => true,
            'is_featured' => false,
        ]));
    }

    /**
     * Build the blog payload from a Facebook post, including shared-source data.
     */
    protected function buildBlogPostData(array $facebookPost, ?BlogPost $existingPost = null)
    {
        $originalPost = $this->resolveOriginalPost($facebookPost);
        $summaryText = $this->extractSummaryText($facebookPost, $originalPost);
        $content = $this->buildContent($facebookPost, $originalPost, $summaryText);

        if (empty($content)) {
            $content = 'Publication Facebook du ' . date('d/m/Y', strtotime($facebookPost['created_time']));
        }

        $titleSource = $this->normalizeInlineText($summaryText ?: $content);
        $title = Str::limit($titleSource, 60, '...');
        $excerpt = Str::limit($titleSource, 200);
        $featuredImage = $existingPost?->featured_image;
        $videoUrl = $existingPost?->video_url;
        $imageUrl = $this->extractBestImageUrl($facebookPost, $originalPost);
        $resolvedVideoUrl = $this->extractBestVideoUrl($facebookPost, $originalPost);

        if (!empty($imageUrl)) {
            $downloadedImage = $this->downloadImage($imageUrl, $facebookPost['id']);

            if (!empty($downloadedImage)) {
                $featuredImage = $downloadedImage;
            }
        }

        if (!empty($resolvedVideoUrl)) {
            $videoUrl = $resolvedVideoUrl;
        }

        return [
            'facebook_post_id' => $facebookPost['id'],
            'title' => $title,
            'slug' => $existingPost?->slug ?: BlogPost::generateSlug($title),
            'excerpt' => $excerpt,
            'content' => $content,
            'featured_image' => $featuredImage,
            'video_url' => $videoUrl,
            'published_at' => $facebookPost['created_time'] ?? $existingPost?->published_at,
            'meta_title' => $title,
            'meta_description' => $excerpt,
        ];
    }

    /**
     * Resolve the original post when the current item is a share.
     */
    protected function resolveOriginalPost(array $facebookPost)
    {
        $originalPostId = $this->extractOriginalPostId($facebookPost);

        if (empty($originalPostId) || $originalPostId === ($facebookPost['id'] ?? null)) {
            return null;
        }

        try {
            return $this->fetchPostById($originalPostId);
        } catch (Exception $e) {
            \Log::warning('Failed to resolve shared Facebook post', [
                'facebook_post_id' => $facebookPost['id'] ?? null,
                'original_post_id' => $originalPostId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Extract the original post ID from a shared post attachment.
     */
    protected function extractOriginalPostId(array $facebookPost)
    {
        $attachments = $facebookPost['attachments']['data'] ?? [];

        foreach ($attachments as $attachment) {
            $url = $attachment['url'] ?? null;

            if (empty($url)) {
                continue;
            }

            $postId = $this->extractPostIdFromFacebookUrl($url);

            if (!empty($postId)) {
                return $postId;
            }
        }

        return null;
    }

    /**
     * Parse a canonical Facebook post ID from a public post URL.
     */
    protected function extractPostIdFromFacebookUrl($url)
    {
        $parts = parse_url(html_entity_decode((string) $url));

        if (empty($parts['path'])) {
            return null;
        }

        parse_str($parts['query'] ?? '', $query);
        $path = trim($parts['path'], '/');

        if (!empty($query['story_fbid']) && !empty($query['id'])) {
            return "{$query['id']}_{$query['story_fbid']}";
        }

        if (preg_match('~^(\d+)/posts/(\d+)~', $path, $matches)) {
            return "{$matches[1]}_{$matches[2]}";
        }

        if (preg_match('~^([^/]+)/posts/(\d+)~', $path, $matches) && ctype_digit($matches[1])) {
            return "{$matches[1]}_{$matches[2]}";
        }

        return null;
    }

    /**
     * Build the primary summary text used for title and excerpt.
     */
    protected function extractSummaryText(array $facebookPost, ?array $originalPost = null)
    {
        $currentText = $this->extractMeaningfulText($facebookPost);

        if (!empty($currentText)) {
            return $currentText;
        }

        $originalText = $this->extractMeaningfulText($originalPost);

        if (!empty($originalText)) {
            return $originalText;
        }

        return 'Publication Facebook du ' . date('d/m/Y', strtotime($facebookPost['created_time']));
    }

    /**
     * Build the body content shown on the blog detail page.
     */
    protected function buildContent(array $facebookPost, ?array $originalPost, $fallbackText)
    {
        $parts = [];
        $currentText = trim((string) ($facebookPost['message'] ?? ''));
        $originalText = trim((string) ($originalPost['message'] ?? ''));

        if ($this->isMeaningfulText($currentText)) {
            $parts[] = $currentText;
        }

        if ($this->isMeaningfulText($originalText) && !in_array($originalText, $parts, true)) {
            $parts[] = empty($parts)
                ? $originalText
                : "Publication d'origine :\n{$originalText}";
        }

        if (empty($parts)) {
            $fallback = $this->extractMeaningfulText($facebookPost) ?: $this->extractMeaningfulText($originalPost) ?: $fallbackText;

            if ($this->isMeaningfulText($fallback)) {
                $parts[] = $fallback;
            }
        }

        return implode("\n\n", $parts);
    }

    /**
     * Extract the best human-readable text from a Facebook post payload.
     */
    protected function extractMeaningfulText(?array $facebookPost)
    {
        if (empty($facebookPost)) {
            return null;
        }

        $attachments = $facebookPost['attachments']['data'] ?? [];
        $candidates = [];

        $candidates[] = $facebookPost['message'] ?? null;

        foreach ($attachments as $attachment) {
            $candidates[] = $attachment['title'] ?? null;
            $candidates[] = $attachment['description'] ?? null;
        }

        $candidates[] = $facebookPost['story'] ?? null;

        foreach ($candidates as $candidate) {
            $candidate = trim((string) $candidate);

            if ($this->isMeaningfulText($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Reject placeholder or generic attachment text.
     */
    protected function isMeaningfulText($text)
    {
        $text = trim((string) $text);

        if ($text === '') {
            return false;
        }

        if (Str::contains($text, "This content isn't available right now")) {
            return false;
        }

        if (Str::contains($text, "When this happens, it's usually because")) {
            return false;
        }

        if (preg_match("/^(Photos|Video)s? from .+ post$/i", $text)) {
            return false;
        }

        return true;
    }

    /**
     * Prefer the original shared image when one is available.
     */
    protected function extractBestImageUrl(array $facebookPost, ?array $originalPost = null)
    {
        if (!empty($originalPost)) {
            $imageUrl = $this->extractImageUrlFromPost($originalPost);

            if (!empty($imageUrl)) {
                return $imageUrl;
            }
        }

        return $this->extractImageUrlFromPost($facebookPost);
    }

    /**
     * Prefer the original shared video when one is available.
     */
    protected function extractBestVideoUrl(array $facebookPost, ?array $originalPost = null)
    {
        if (!empty($originalPost)) {
            $videoUrl = $this->extractVideoUrlFromPost($originalPost);

            if (!empty($videoUrl)) {
                return $videoUrl;
            }
        }

        return $this->extractVideoUrlFromPost($facebookPost);
    }

    /**
     * Extract the best image URL from a Facebook post payload.
     */
    protected function extractImageUrlFromPost(?array $facebookPost)
    {
        if (empty($facebookPost)) {
            return null;
        }

        if (!empty($facebookPost['full_picture'])) {
            return $facebookPost['full_picture'];
        }

        foreach ($facebookPost['attachments']['data'] ?? [] as $attachment) {
            $imageUrl = $this->extractImageUrlFromAttachment($attachment);

            if (!empty($imageUrl)) {
                return $imageUrl;
            }
        }

        return null;
    }

    /**
     * Extract the best video URL from a Facebook post payload.
     */
    protected function extractVideoUrlFromPost(?array $facebookPost)
    {
        if (empty($facebookPost)) {
            return null;
        }

        foreach ($facebookPost['attachments']['data'] ?? [] as $attachment) {
            $videoUrl = $this->extractVideoUrlFromAttachment($attachment);

            if (!empty($videoUrl)) {
                return $videoUrl;
            }
        }

        if ($this->looksLikeVideoUrl($facebookPost['permalink_url'] ?? null)) {
            return $facebookPost['permalink_url'];
        }

        return null;
    }

    /**
     * Extract an image URL from one attachment or subattachment.
     */
    protected function extractImageUrlFromAttachment(array $attachment)
    {
        if (!empty($attachment['media']['image']['src'])) {
            return $attachment['media']['image']['src'];
        }

        foreach ($attachment['subattachments']['data'] ?? [] as $subattachment) {
            $imageUrl = $this->extractImageUrlFromAttachment($subattachment);

            if (!empty($imageUrl)) {
                return $imageUrl;
            }
        }

        return null;
    }

    /**
     * Extract a video URL from one attachment or subattachment.
     */
    protected function extractVideoUrlFromAttachment(array $attachment)
    {
        $type = strtolower((string) ($attachment['type'] ?? ''));
        $candidates = [
            $attachment['media']['source'] ?? null,
            $attachment['url'] ?? null,
            $attachment['target']['url'] ?? null,
        ];

        if (Str::contains($type, 'video')) {
            foreach ($candidates as $candidate) {
                if ($this->looksLikeVideoUrl($candidate)) {
                    return $candidate;
                }
            }

            foreach ($candidates as $candidate) {
                if (!empty($candidate) && filter_var($candidate, FILTER_VALIDATE_URL)) {
                    return $candidate;
                }
            }
        }

        foreach ($attachment['subattachments']['data'] ?? [] as $subattachment) {
            $videoUrl = $this->extractVideoUrlFromAttachment($subattachment);

            if (!empty($videoUrl)) {
                return $videoUrl;
            }
        }

        return null;
    }

    /**
     * Determine whether a URL can be used as a video source or embed target.
     */
    protected function looksLikeVideoUrl($url)
    {
        $url = trim((string) $url);

        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = strtolower((string) parse_url($url, PHP_URL_PATH));

        if (preg_match('~\.(mp4|webm|ogg|m3u8)(?:\?.*)?$~i', $url)) {
            return true;
        }

        if (Str::contains($host, ['youtu.be', 'youtube.com', 'vimeo.com', 'fb.watch', 'fbcdn.net'])) {
            return true;
        }

        return Str::contains($host, 'facebook.com') && Str::contains($path, ['/videos/', '/watch/', '/reel/']);
    }

    /**
     * Infer the Facebook post ID from the stored image filename.
     */
    protected function inferFacebookPostIdFromBlogPost(BlogPost $blogPost)
    {
        $filename = basename((string) $blogPost->featured_image);

        if (preg_match('/(\d+_\d+)_\d+\.(?:jpe?g|png|gif|webp)$/i', $filename, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Normalize multiline text for titles and excerpts.
     */
    protected function normalizeInlineText($text)
    {
        return trim(preg_replace('/\s+/u', ' ', (string) $text));
    }

    /**
     * Resolve the admin user used for imported blog posts.
     */
    protected function resolveAdminUser()
    {
        $adminUser = User::where('role', 'admin')->first();

        if ($adminUser) {
            return $adminUser;
        }

        $adminUser = User::first();

        if (!$adminUser) {
            throw new Exception('No user found to assign imported Facebook posts');
        }

        return $adminUser;
    }

    /**
     * Download image from Facebook
     */
    protected function downloadImage($imageUrl, $postId)
    {
        return $this->downloadRemoteImage($imageUrl, $postId, 'blog/facebook', 'Failed to download Facebook image');
    }

    /**
     * Download an imported Facebook event image locally.
     */
    protected function downloadEventImage($imageUrl, $eventId)
    {
        return $this->downloadRemoteImage($imageUrl, $eventId, 'events/facebook', 'Failed to download Facebook event image');
    }

    /**
     * Download a remote image into the public disk.
     */
    protected function downloadRemoteImage($imageUrl, $resourceId, $directory, $logMessage)
    {
        try {
            $response = Http::timeout(30)->get($imageUrl);
            
            if (!$response->successful()) {
                throw new Exception('Failed to download image');
            }

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $filename = $directory . '/' . $resourceId . '_' . time() . '.jpg';
            
            Storage::disk('public')->put($filename, $response->body());

            return $filename;

        } catch (Exception $e) {
            \Log::warning($logMessage, [
                'url' => $imageUrl,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get statistics about Facebook imports
     */
    public function getStats()
    {
        $totalImported = BlogPost::whereNotNull('facebook_post_id')->count();
        $lastImport = BlogPost::whereNotNull('facebook_post_id')
                              ->orderBy('created_at', 'desc')
                              ->first();

        return [
            'total_imported' => $totalImported,
            'last_import_date' => $lastImport ? $lastImport->created_at : null,
            'last_import_title' => $lastImport ? $lastImport->title : null,
        ];
    }
}
