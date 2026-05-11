<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'facebook_post_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'video_url',
        'author_id',
        'category',
        'tags',
        'published_at',
        'is_published',
        'is_featured',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];

    /**
     * Relation avec l'auteur
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope pour les articles publiés
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope pour les articles en vedette
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope pour les articles récents
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Accessor pour le temps de lecture
     */
    public function getReadingTimeAttribute()
    {
        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / $wordsPerMinute);
        
        return $minutes;
    }

    /**
     * Accessor pour la date formatée
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M Y') : $this->created_at->format('d M Y');
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Générer automatiquement un slug unique
     */
    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Boot method pour auto-générer le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateSlug($post->title);
            }
        });
    }

    /**
     * Obtenir l'URL de l'image à la une
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return \Storage::url($this->featured_image);
        }
        return asset('images/default-blog-post.jpg');
    }

    /**
     * Determine whether the post has a video attached.
     */
    public function getHasVideoAttribute()
    {
        return !empty($this->video_url);
    }

    /**
     * Resolve the video platform from the configured URL.
     */
    public function getVideoPlatformAttribute()
    {
        $videoUrl = trim((string) $this->video_url);

        if ($videoUrl === '') {
            return null;
        }

        $host = strtolower((string) parse_url($videoUrl, PHP_URL_HOST));

        if (preg_match('~(?:youtube\.com/(?:watch|embed|shorts)|youtu\.be/)~i', $videoUrl)) {
            return 'youtube';
        }

        if (preg_match('~vimeo\.com/~i', $videoUrl)) {
            return 'vimeo';
        }

        if (Str::contains($host, ['facebook.com', 'fb.watch'])) {
            return 'facebook';
        }

        if (Str::contains($host, ['fbcdn.net', 'cdninstagram.com'])) {
            return 'direct';
        }

        if (preg_match('~\.(mp4|webm|ogg|m3u8)(?:\?.*)?$~i', $videoUrl)) {
            return 'direct';
        }

        return 'external';
    }

    /**
     * Return whether the video should be rendered with an iframe.
     */
    public function getUsesIframeVideoAttribute()
    {
        return in_array($this->video_platform, ['youtube', 'vimeo', 'facebook'], true);
    }

    /**
     * Return whether the video should be rendered in an HTML5 player.
     */
    public function getIsDirectVideoAttribute()
    {
        return $this->video_platform === 'direct';
    }

    /**
     * Build an embeddable URL when the provider supports iframe embeds.
     */
    public function getVideoEmbedUrlAttribute()
    {
        $videoUrl = trim((string) $this->video_url);

        if ($videoUrl === '') {
            return null;
        }

        if ($this->video_platform === 'youtube') {
            if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{6,})~', $videoUrl, $matches)) {
                return 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0';
            }
        }

        if ($this->video_platform === 'vimeo') {
            if (preg_match('~vimeo\.com/(?:.*?/)?(\d+)~', $videoUrl, $matches)) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        }

        if ($this->video_platform === 'facebook') {
            return 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($videoUrl) . '&show_text=false&width=1280';
        }

        return null;
    }
}
