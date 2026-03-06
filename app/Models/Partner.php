<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'website',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? \Illuminate\Support\Facades\Storage::url($this->logo) : null;
    }

    /**
     * Scope to get only active partners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
