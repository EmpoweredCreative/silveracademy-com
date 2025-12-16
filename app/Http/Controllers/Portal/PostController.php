<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request): Response
    {
        $posts = Post::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Portal/Posts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['search', 'type']),
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): Response
    {
        return Inertia::render('Portal/Posts/Create');
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:news,event',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'event_start_date' => 'required_if:type,event|nullable|date',
            'event_end_date' => 'nullable|date|after_or_equal:event_start_date',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:500',
            'publish_now' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'event_start_date' => $validated['event_start_date'] ?? null,
            'event_end_date' => $validated['event_end_date'] ?? null,
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'published_at' => $request->boolean('publish_now') ? now() : null,
        ]);

        return redirect()->route('portal.posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): Response
    {
        return Inertia::render('Portal/Posts/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'type' => 'required|in:news,event',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'boolean',
            'event_start_date' => 'required_if:type,event|nullable|date',
            'event_end_date' => 'nullable|date|after_or_equal:event_start_date',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:500',
            'published_at' => 'nullable|date',
        ]);

        // Handle image
        $imagePath = $post->image_path;
        
        if ($request->boolean('remove_image') && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'event_start_date' => $validated['event_start_date'] ?? null,
            'event_end_date' => $validated['event_end_date'] ?? null,
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'published_at' => $validated['published_at'] ?? $post->published_at,
        ]);

        return redirect()->route('portal.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Post $post)
    {
        // Delete associated image
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('portal.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Post $post)
    {
        $post->update([
            'published_at' => $post->published_at ? null : now(),
        ]);

        $status = $post->published_at ? 'published' : 'unpublished';

        return back()->with('success', "Post {$status} successfully.");
    }
}

