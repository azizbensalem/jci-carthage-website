<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_email',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'google_connected',
    ];

    protected $casts = [
        'google_token_expires_at' => 'datetime',
        'google_connected' => 'boolean',
    ];

    /**
     * Get the organization settings (singleton pattern)
     */
    public static function getSettings()
    {
        return static::firstOrCreate([]);
    }

    /**
     * Check if Google account is connected
     */
    public function isGoogleConnected()
    {
        return $this->google_connected && !empty($this->google_access_token);
    }
}
