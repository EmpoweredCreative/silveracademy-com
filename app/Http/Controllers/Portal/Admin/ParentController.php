<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class ParentController extends Controller
{
    /**
     * Display a listing of all parent accounts.
     */
    public function index(Request $request): Response
    {
        $query = User::where('role', User::ROLE_PARENT)
            ->with(['children.grade', 'children.classroom']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $parents = $query->orderBy('name')
            ->paginate(20)
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'children' => $user->children->map(fn($s) => [
                        'id' => $s->id,
                        'name' => $s->name,
                        'grade_name' => $s->grade?->name,
                        'classroom_name' => $s->classroom?->name,
                    ]),
                    'children_count' => $user->children->count(),
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                ];
            });

        // Get counts
        $counts = [
            'total' => User::where('role', User::ROLE_PARENT)->count(),
            'verified' => User::where('role', User::ROLE_PARENT)->whereNotNull('email_verified_at')->count(),
            'pending' => User::where('role', User::ROLE_PARENT)->whereNull('email_verified_at')->count(),
        ];

        return Inertia::render('Portal/Admin/Parents/Index', [
            'parents' => $parents,
            'counts' => $counts,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for editing a parent account.
     */
    public function edit(User $parent): Response
    {
        // Ensure we're editing a parent
        if ($parent->role !== User::ROLE_PARENT) {
            abort(404);
        }

        // Get all students for assignment
        $students = Student::with(['grade', 'classroom'])
            ->orderBy('name')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'grade_name' => $s->grade?->name,
                'classroom_name' => $s->classroom?->name,
                'display_name' => $s->name . ' (' . ($s->grade?->name ?? 'No Grade') . ')',
            ]);

        $parent->load('children');

        return Inertia::render('Portal/Admin/Parents/Edit', [
            'parent' => [
                'id' => $parent->id,
                'name' => $parent->name,
                'email' => $parent->email,
                'children_ids' => $parent->children->pluck('id')->toArray(),
                'email_verified_at' => $parent->email_verified_at,
                'created_at' => $parent->created_at,
            ],
            'students' => $students,
        ]);
    }

    /**
     * Update the specified parent account.
     */
    public function update(Request $request, User $parent)
    {
        // Ensure we're editing a parent
        if ($parent->role !== User::ROLE_PARENT) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $parent->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'children_ids' => 'nullable|array',
            'children_ids.*' => 'exists:students,id',
        ]);

        $parent->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $parent->update(['password' => Hash::make($validated['password'])]);
        }

        // Update children assignments
        $parent->children()->sync($validated['children_ids'] ?? []);

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent account updated successfully.');
    }

    /**
     * Remove the specified parent account.
     */
    public function destroy(User $parent)
    {
        // Ensure we're deleting a parent
        if ($parent->role !== User::ROLE_PARENT) {
            abort(404);
        }

        // Detach from children (doesn't delete students)
        $parent->children()->detach();

        $parent->delete();

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent account deleted successfully.');
    }

    /**
     * Resend verification email to parent.
     */
    public function resendVerification(User $parent)
    {
        if ($parent->role !== User::ROLE_PARENT) {
            abort(404);
        }

        if ($parent->hasVerifiedEmail()) {
            return back()->with('info', 'Email is already verified.');
        }

        $parent->sendEmailVerificationNotification();

        return back()->with('success', 'Verification email sent successfully.');
    }

    /**
     * Manually verify a parent's email.
     */
    public function verifyEmail(User $parent)
    {
        if ($parent->role !== User::ROLE_PARENT) {
            abort(404);
        }

        if ($parent->hasVerifiedEmail()) {
            return back()->with('info', 'Email is already verified.');
        }

        $parent->markEmailAsVerified();

        return back()->with('success', 'Email marked as verified.');
    }
}

