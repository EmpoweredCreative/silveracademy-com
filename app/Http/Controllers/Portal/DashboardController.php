<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\LunchMenu;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        // Get current week's lunch menu
        $startOfWeek = now()->startOfWeek(); // Monday
        $currentLunchMenu = LunchMenu::where('week_start', '>=', $startOfWeek->copy()->subWeek())
            ->where('week_start', '<=', now())
            ->orderBy('week_start', 'desc')
            ->first();

        // Get upcoming events (from Posts)
        $upcomingEvents = Post::where('type', 'event')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('event_start_date', '>=', now())
            ->orderBy('event_start_date', 'asc')
            ->take(5)
            ->get();

        // Get latest announcements (from Posts)
        $recentAnnouncements = Post::where('type', 'news')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return Inertia::render('Portal/Dashboard', [
            'user' => $user,
            'currentLunchMenu' => $currentLunchMenu,
            'upcomingEvents' => $upcomingEvents,
            'recentAnnouncements' => $recentAnnouncements,
        ]);
    }
}
