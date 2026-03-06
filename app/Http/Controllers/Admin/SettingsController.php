<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display the settings page
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'google');
        $settings = OrganizationSettings::getSettings();
        
        // Generate the exact redirect URI that should be used
        $redirectUri = config('services.google.redirect');
        if (empty($redirectUri)) {
            // Fallback to current request URL if not configured
            $redirectUri = route('admin.google-account.callback', [], false);
            $redirectUri = url($redirectUri);
        }
        
        // Get log files
        $logFiles = [];
        $logContent = null;
        $selectedLog = $request->get('log');
        
        $logPath = storage_path('logs');
        if (File::exists($logPath)) {
            $logFiles = collect(File::files($logPath))
                ->filter(function ($file) {
                    return pathinfo($file, PATHINFO_EXTENSION) === 'log';
                })
                ->map(function ($file) {
                    return [
                        'name' => $file->getFilename(),
                        'size' => $file->getSize(),
                        'modified' => $file->getMTime(),
                    ];
                })
                ->sortByDesc('modified')
                ->values()
                ->toArray();
            
            if ($selectedLog && File::exists($logPath . '/' . $selectedLog)) {
                $logContent = File::get($logPath . '/' . $selectedLog);
                // Get last 500 lines
                $lines = explode("\n", $logContent);
                $logContent = implode("\n", array_slice($lines, -500));
            }
        }
        
        return view('admin.settings.index', compact('settings', 'tab', 'logFiles', 'logContent', 'selectedLog', 'redirectUri'));
    }
}
