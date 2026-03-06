<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'icon',
        'icon_color',
        'link',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? \Illuminate\Support\Facades\Storage::url($this->image) : null;
    }
}
