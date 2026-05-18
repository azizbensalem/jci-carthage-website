<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'facebook_event_id',
        'type',
        'title',
        'description',
        'image',
        'icon',
        'icon_color',
        'link',
        'starts_at',
        'ends_at',
        'location_name',
        'order',
        'is_featured',
        'is_active',
        'show_on_website',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'show_on_website' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Event types
     */
    public static function getTypes()
    {
        return [
            'project' => 'Projet',
            'assembly' => 'Assemblée',
            'meeting' => 'Réunion',
            'development' => 'Développement',
            'training-session' => 'Session de Formation',
            'forum-session' => 'Session Forum',
        ];
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? \Illuminate\Support\Facades\Storage::url($this->image) : null;
    }

    /**
     * Scope to get only projects (for website display)
     */
    public function scopeProjects($query)
    {
        return $query->where('type', 'project')->where('show_on_website', true);
    }

    /**
     * Scope to get events by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get visible events
     */
    public function scopeVisible($query)
    {
        return $query->where('show_on_website', true)->where('is_active', true);
    }

    /**
     * Scope to order public events by their real start date.
     */
    public function scopeOrderedForEvents($query)
    {
        return $query->orderByDesc('starts_at')
                     ->orderBy('order')
                     ->orderByDesc('created_at');
    }
}
