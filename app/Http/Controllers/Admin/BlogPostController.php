<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogPost::with('author')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
        
        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => '

required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        // Convert tags string to array
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        // Set author
        $validated['author_id'] = auth()->id();

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = BlogPost::generateSlug($validated['title']);
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')
                        ->with('success', __('blog.post_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blog)
    {
        return redirect()->route('blog.show', $blog->slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blog)
    {
        $categories = $this->getCategories();
        // Convert tags array to comma-separated string
        $blog->tags_string = is_array($blog->tags) ? implode(', ', $blog->tags) : '';
        
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        // Convert tags string to array
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        $blog->update($validated);

        return redirect()->route('admin.blog.index')
                        ->with('success', __('blog.post_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blog)
    {
        // Delete featured image
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
                        ->with('success', __('blog.post_deleted_successfully'));
    }

    /**
     * Get available categories
     */
    private function getCategories()
    {
        return [
            'News' => __('blog.category_news'),
            'Events' => __('blog.category_events'),
            'Projects' => __('blog.category_projects'),
            'Training' => __('blog.category_training'),
            'Community' => __('blog.category_community'),
        ];
    }
}
