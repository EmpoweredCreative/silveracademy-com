<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Display a single event.
     */
    public function show(Post $post): Response
    {
        // Ensure it's an event post, is published, and is public
        if ($post->type !== 'event' || !$post->published_at || !$post->is_public) {
            abort(404);
        }

        // Get other upcoming public events
        $upcomingEvents = Post::with('author')
            ->published()
            ->upcoming()
            ->publicEvents()
            ->where('id', '!=', $post->id)
            ->orderBy('event_start_date', 'asc')
            ->take(3)
            ->get();

        return Inertia::render('Marketing/EventShow', [
            'event' => $post->load('author'),
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}



