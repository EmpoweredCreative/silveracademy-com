<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffDirectory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StaffDirectoryController extends Controller
{
    /**
     * List public staff directory entries (for the website staff page).
     */
    public function index(): Response
    {
        $entries = StaffDirectory::orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'title' => $e->title,
                'department' => $e->department,
                'photo' => $e->photo,
            ]);

        return Inertia::render('Portal/Admin/StaffDirectory/Index', [
            'entries' => $entries,
        ]);
    }

    /**
     * Show create form.
     */
    public function create(): Response
    {
        return Inertia::render('Portal/Admin/StaffDirectory/Create');
    }

    /**
     * Store a new staff directory entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'photo' => 'nullable|string|max:500',
        ]);

        $maxOrder = StaffDirectory::max('sort_order') ?? -1;
        $validated['sort_order'] = $maxOrder + 1;

        StaffDirectory::create($validated);

        return redirect()->route('admin.staff-directory.index')
            ->with('success', 'Staff member added to the public directory.');
    }

    /**
     * Show edit form.
     */
    public function edit(StaffDirectory $staffDirectory): Response
    {
        return Inertia::render('Portal/Admin/StaffDirectory/Edit', [
            'entry' => [
                'id' => $staffDirectory->id,
                'name' => $staffDirectory->name,
                'title' => $staffDirectory->title,
                'department' => $staffDirectory->department,
                'photo' => $staffDirectory->photo,
            ],
        ]);
    }

    /**
     * Update a staff directory entry.
     */
    public function update(Request $request, StaffDirectory $staffDirectory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'photo' => 'nullable|string|max:500',
        ]);

        $staffDirectory->update($validated);

        return redirect()->route('admin.staff-directory.index')
            ->with('success', 'Staff directory entry updated.');
    }

    /**
     * Delete a staff directory entry.
     */
    public function destroy(StaffDirectory $staffDirectory)
    {
        $staffDirectory->delete();

        return redirect()->route('admin.staff-directory.index')
            ->with('success', 'Staff member removed from the public directory.');
    }
}
