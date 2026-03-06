<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class FacebookImportService
{
    protected $pageId;
    protected $accessToken;

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
        $stats = [
            'imported' => 0,
            'skipped' => 0,
            'errors' => 0,
            'error_messages' => []
        ];

        try {
            $posts = $this->fetchLatestPosts($limit);

            if (empty($posts)) {
                throw new Exception('No posts found from Facebook');
            }

            foreach ($posts as $post) {
                try {
                    // Check if already exists
                    if (BlogPost::where('facebook_post_id', $post['id'])->exists()) {
                        $stats['skipped']++;
                        continue;
                    }

                    // Convert and save
                    $this->convertToBlog($post);
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
     * Fetch latest posts from Facebook
     */
    protected function fetchLatestPosts($limit = 10)
    {
        if (empty($this->pageId) || empty($this->accessToken)) {
            throw new Exception('Facebook credentials not configured. Please set FACEBOOK_PAGE_ID and FACEBOOK_ACCESS_TOKEN in .env');
        }

        $url = "https://graph.facebook.com/v18.0/{$this->pageId}/posts";
        
        $response = Http::timeout(30)->get($url, [
            'fields' => 'id,message,created_time,full_picture,permalink_url,attachments{media,subattachments}',
            'limit' => $limit,
            'access_token' => $this->accessToken
        ]);

        if (!$response->successful()) {
            $error = $response->json('error.message', 'Unknown error');
            throw new Exception("Facebook API Error: {$error}");
        }

        return $response->json('data', []);
    }

    /**
     * Convert Facebook post to BlogPost
     */
    protected function convertToBlog($facebookPost)
    {
        $message = $facebookPost['message'] ?? '';
        
        if (empty($message)) {
            $message = 'Publication Facebook du ' . date('d/m/Y', strtotime($facebookPost['created_time']));
        }

        // Generate title from first 60 characters
        $title = Str::limit($message, 60, '...');
        
        // Generate excerpt from first 200 characters
        $excerpt = Str::limit($message, 200);

        // Download image if available
        $featuredImage = null;
        if (!empty($facebookPost['full_picture'])) {
            try {
                $featuredImage = $this->downloadImage($facebookPost['full_picture'], $facebookPost['id']);
            } catch (Exception $e) {
                \Log::warning('Failed to download Facebook image', ['error' => $e->getMessage()]);
            }
        }

        // Get admin user as author (ID 1)
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::first(); // Fallback to first user
        }

        // Create blog post
        BlogPost::create([
            'facebook_post_id' => $facebookPost['id'],
            'title' => $title,
            'slug' => BlogPost::generateSlug($title),
            'excerpt' => $excerpt,
            'content' => $message,
            'featured_image' => $featuredImage,
            'author_id' => $adminUser->id,
            'category' => 'Facebook',
            'tags' => ['Facebook', 'Import', 'Social Media'],
            'published_at' => $facebookPost['created_time'],
            'is_published' => true,
            'is_featured' => false,
            'meta_title' => $title,
            'meta_description' => $excerpt,
        ]);
    }

    /**
     * Download image from Facebook
     */
    protected function downloadImage($imageUrl, $postId)
    {
        try {
            $response = Http::timeout(30)->get($imageUrl);
            
            if (!$response->successful()) {
                throw new Exception('Failed to download image');
            }

            // Create directory if not exists
            $directory = 'blog/facebook';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Generate filename
            $filename = $directory . '/' . $postId . '_' . time() . '.jpg';
            
            // Save image
            Storage::disk('public')->put($filename, $response->body());

            return $filename;

        } catch (Exception $e) {
            \Log::warning('Failed to download Facebook image', [
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
