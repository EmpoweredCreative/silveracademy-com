<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    /**
     * Display the news and events listing page.
     */
    public function index(): Response
    {
        $news = Post::with('author')
            ->published()
            ->news()
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        $events = Post::with('author')
            ->published()
            ->upcoming()
            ->orderBy('event_start_date', 'asc')
            ->take(10)
            ->get();

        return Inertia::render('Marketing/NewsEvents', [
            'news' => $news,
            'events' => $events,
        ]);
    }

    /**
     * Display a single news article.
     */
    public function show(Post $post): Response
    {
        // Ensure it's a news post and is published
        if ($post->type !== 'news' || !$post->published_at) {
            abort(404);
        }

        // Get related news articles
        $relatedNews = Post::with('author')
            ->published()
            ->news()
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return Inertia::render('Marketing/NewsShow', [
            'post' => $post->load('author'),
            'relatedNews' => $relatedNews,
        ]);
    }
}

