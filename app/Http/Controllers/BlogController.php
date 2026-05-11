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
        $filters = [
            'search' => trim((string) $request->input('search', '')),
            'category' => trim((string) $request->input('category', '')),
            'date_from' => (string) $request->input('date_from', ''),
            'date_to' => (string) $request->input('date_to', ''),
        ];

        $hasActiveFilters = collect($filters)->filter()->isNotEmpty();

        $query = BlogPost::with('author')
                        ->published()
                        ->orderBy('published_at', 'desc');

        // Filter by category if provided
        if ($filters['category'] !== '') {
            $query->byCategory($filters['category']);
        }

        // Search
        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('published_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('published_at', '<=', $filters['date_to']);
        }

        $posts = $query->paginate(9)->withQueryString();

        // Get featured posts
        $featuredPosts = $hasActiveFilters
            ? collect()
            : BlogPost::with('author')
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

        return view('blog.index', compact(
            'posts',
            'featuredPosts',
            'categories',
            'recentPosts',
            'filters',
            'hasActiveFilters'
        ));
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
        return redirect()->route('blog.index', ['category' => $category]);
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
