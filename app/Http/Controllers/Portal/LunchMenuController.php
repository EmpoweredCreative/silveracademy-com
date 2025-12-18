<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\LunchMenu;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class LunchMenuController extends Controller
{
    /**
     * Display the lunch menu page.
     */
    public function index(): Response
    {
        $today = now()->startOfDay();
        $startOfWeek = now()->startOfWeek(); // Monday

        // Get current week's menu
        $currentMenu = LunchMenu::with('user')
            ->where('week_start', '<=', $today)
            ->where('week_start', '>=', $startOfWeek->copy()->subWeek())
            ->orderBy('week_start', 'desc')
            ->first();

        // Get upcoming menus
        $upcomingMenus = LunchMenu::with('user')
            ->where('week_start', '>', $today)
            ->orderBy('week_start', 'asc')
            ->take(6)
            ->get();

        // Get past menus (admin only)
        $pastMenus = [];
        if (auth()->user()->isAdmin()) {
            $pastMenus = LunchMenu::with('user')
                ->where('week_start', '<', $startOfWeek)
                ->orderBy('week_start', 'desc')
                ->take(10)
                ->get();
        }

        return Inertia::render('Portal/Lunch/Index', [
            'currentMenu' => $currentMenu,
            'upcomingMenus' => $upcomingMenus,
            'pastMenus' => $pastMenus,
        ]);
    }

    /**
     * Show the form for creating a new lunch menu.
     */
    public function create(): Response
    {
        $this->authorizeAdmin();

        return Inertia::render('Portal/Lunch/Create');
    }

    /**
     * Store a newly created lunch menu.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'week_start' => ['required', 'date'],
            'content' => ['required', 'string'],
        ]);

        LunchMenu::create([
            'user_id' => auth()->id(),
            'week_start' => $validated['week_start'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('portal.lunch.index')
            ->with('success', 'Lunch menu created successfully.');
    }

    /**
     * Show the form for editing the specified lunch menu.
     */
    public function edit(LunchMenu $lunch): Response
    {
        $this->authorizeAdmin();

        return Inertia::render('Portal/Lunch/Edit', [
            'menu' => $lunch,
        ]);
    }

    /**
     * Update the specified lunch menu.
     */
    public function update(Request $request, LunchMenu $lunch): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'week_start' => ['required', 'date'],
            'content' => ['required', 'string'],
        ]);

        $lunch->update($validated);

        return redirect()->route('portal.lunch.index')
            ->with('success', 'Lunch menu updated successfully.');
    }

    /**
     * Remove the specified lunch menu.
     */
    public function destroy(LunchMenu $lunch): RedirectResponse
    {
        $this->authorizeAdmin();

        $lunch->delete();

        return redirect()->route('portal.lunch.index')
            ->with('success', 'Lunch menu deleted successfully.');
    }

    /**
     * Check if current user is admin.
     */
    protected function authorizeAdmin(): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

