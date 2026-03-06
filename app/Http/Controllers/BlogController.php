<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('author')
                        ->published()
                        ->orderBy('published_at', 'desc');

        // Filter by category if provided
        if ($request->has('category') && !empty($request->category)) {
            $query->byCategory($request->category);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(9);
        
        // Get featured posts
        $featuredPosts = BlogPost::with('author')
                                ->published()
                                ->featured()
                                ->recent(3)
                                ->get();

        // Get categories with count
        $categories = $this->getCategoriesWithCount();

        // Recent posts for sidebar
        $recentPosts = BlogPost::published()
                              ->recent(5)
                              ->get();

        return view('blog.index', compact('posts', 'featuredPosts', 'categories', 'recentPosts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::with('author')
                       ->where('slug', $slug)
                       ->published()
                       ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Get related posts (same category)
        $relatedPosts = BlogPost::with('author')
                               ->published()
                               ->where('id', '!=', $post->id)
                               ->where('category', $post->category)
                               ->recent(3)
                               ->get();

        // Recent posts for sidebar
        $recentPosts = BlogPost::published()
                              ->recent(5)
                              ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'recentPosts'));
    }

    /**
     * Display posts by category.
     */
    public function category($category)
    {
        $posts = BlogPost::with('author')
                        ->published()
                        ->byCategory($category)
                        ->orderBy('published_at', 'desc')
                        ->paginate(9);

        $categories = $this->getCategoriesWithCount();
        $recentPosts = BlogPost::published()->recent(5)->get();

        return view('blog.category', compact('posts', 'category', 'categories', 'recentPosts'));
    }

    /**
     * Get categories with post count
     */
    private function getCategoriesWithCount()
    {
        return BlogPost::published()
                      ->selectRaw('category, COUNT(*) as count')
                      ->groupBy('category')
                      ->whereNotNull('category')
                      ->get()
                      ->pluck('count', 'category');
    }
}
