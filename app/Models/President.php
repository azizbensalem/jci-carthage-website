<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class President extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'presidency_year',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only visible presidents.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the public photo URL.
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? \Illuminate\Support\Facades\Storage::url($this->photo) : null;
    }
}
