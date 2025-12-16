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
        // Ensure it's an event post and is published
        if ($post->type !== 'event' || !$post->published_at) {
            abort(404);
        }

        // Get other upcoming events
        $upcomingEvents = Post::with('author')
            ->published()
            ->upcoming()
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

