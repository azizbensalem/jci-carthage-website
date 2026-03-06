<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FacebookImportService;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class FacebookImportController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookImportService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    /**
     * Display import page
     */
    public function index()
    {
        $stats = $this->facebookService->getStats();
        
        $facebookPosts = BlogPost::whereNotNull('facebook_post_id')
                                ->orderBy('published_at', 'desc')
                                ->take(20)
                                ->get();

        return view('admin.facebook.import', compact('stats', 'facebookPosts'));
    }

    /**
     * Execute import
     */
    public function import()
    {
        $results = $this->facebookService->import(10);

        $message = __('facebook.import_success', ['count' => $results['imported']]);
        
        if ($results['skipped'] > 0) {
            $message .= ' ' . __('facebook.import_skipped', ['count' => $results['skipped']]);
        }

        if ($results['errors'] > 0) {
            $errorMsg = implode(', ', $results['error_messages']);
            return redirect()->route('admin.facebook.import')
                           ->with('error', __('facebook.import_error') . ': ' . $errorMsg);
        }

        return redirect()->route('admin.facebook.import')
                        ->with('success', $message);
    }
}
