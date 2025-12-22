<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    /**
     * Display a listing of all staff members.
     */
    public function index(): Response
    {
        $staff = User::whereIn('role', [User::ROLE_TEACHER, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])
            ->with('classrooms.grade')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'role_label' => $this->getRoleLabel($user->role),
                    'classrooms' => $user->classrooms->map(fn($c) => [
                        'id' => $c->id,
                        'name' => $c->name,
                        'grade_name' => $c->grade?->name,
                    ]),
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                ];
            });

        // Get counts
        $counts = [
            'total' => $staff->count(),
            'teachers' => $staff->where('role', User::ROLE_TEACHER)->count(),
            'admins' => $staff->whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])->count(),
        ];

        return Inertia::render('Portal/Admin/Staff/Index', [
            'staff' => $staff,
            'counts' => $counts,
        ]);
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create(): Response
    {
        $classrooms = Classroom::with('grade')
            ->orderBy('grade_id')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'grade_name' => $c->grade?->name,
                'display_name' => $c->grade?->name . ' - ' . $c->name,
            ]);

        return Inertia::render('Portal/Admin/Staff/Create', [
            'classrooms' => $classrooms,
            'staffEmailDomain' => config('app.staff_email_domain'),
        ]);
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:teacher,admin',
            'classroom_ids' => 'nullable|array',
            'classroom_ids.*' => 'exists:classrooms,id',
            'send_welcome_email' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Assign classrooms if teacher role
        if ($validated['role'] === User::ROLE_TEACHER && !empty($validated['classroom_ids'])) {
            Classroom::whereIn('id', $validated['classroom_ids'])
                ->update(['teacher_id' => $user->id]);
        }

        // Send welcome email if requested
        if ($request->boolean('send_welcome_email')) {
            event(new Registered($user));
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    /**
     * Show the form for editing a staff member.
     */
    public function edit(User $staff): Response
    {
        // Ensure we're editing a staff member
        if (!in_array($staff->role, [User::ROLE_TEACHER, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])) {
            abort(404);
        }

        $classrooms = Classroom::with('grade')
            ->orderBy('grade_id')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'grade_name' => $c->grade?->name,
                'display_name' => $c->grade?->name . ' - ' . $c->name,
            ]);

        $staff->load('classrooms');

        return Inertia::render('Portal/Admin/Staff/Edit', [
            'staff' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'role' => $staff->role,
                'classroom_ids' => $staff->classrooms->pluck('id')->toArray(),
                'email_verified_at' => $staff->email_verified_at,
            ],
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, User $staff)
    {
        // Ensure we're editing a staff member
        if (!in_array($staff->role, [User::ROLE_TEACHER, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])) {
            abort(404);
        }

        // Prevent super admins from being demoted by non-super-admins
        if ($staff->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'You cannot modify a super admin account.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $staff->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:teacher,admin',
            'classroom_ids' => 'nullable|array',
            'classroom_ids.*' => 'exists:classrooms,id',
        ]);

        // Don't allow changing super admin role
        if ($staff->isSuperAdmin()) {
            unset($validated['role']);
        }

        $staff->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? $staff->role,
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $staff->update(['password' => Hash::make($validated['password'])]);
        }

        // Update classroom assignments
        // First, remove this teacher from all classrooms
        Classroom::where('teacher_id', $staff->id)->update(['teacher_id' => null]);

        // Then assign new classrooms if teacher role
        if (($validated['role'] ?? $staff->role) === User::ROLE_TEACHER && !empty($validated['classroom_ids'])) {
            Classroom::whereIn('id', $validated['classroom_ids'])
                ->update(['teacher_id' => $staff->id]);
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(User $staff)
    {
        // Ensure we're deleting a staff member
        if (!in_array($staff->role, [User::ROLE_TEACHER, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])) {
            abort(404);
        }

        // Prevent deleting super admins
        if ($staff->isSuperAdmin()) {
            return back()->with('error', 'Super admin accounts cannot be deleted.');
        }

        // Prevent self-deletion
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Remove from classrooms
        Classroom::where('teacher_id', $staff->id)->update(['teacher_id' => null]);

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    /**
     * Toggle a staff member's role between teacher and admin.
     */
    public function toggleRole(User $staff)
    {
        // Prevent modifying super admins
        if ($staff->isSuperAdmin()) {
            return back()->with('error', 'Super admin role cannot be changed.');
        }

        $newRole = $staff->role === User::ROLE_TEACHER ? User::ROLE_ADMIN : User::ROLE_TEACHER;
        $staff->update(['role' => $newRole]);

        return back()->with('success', 'Role updated to ' . $this->getRoleLabel($newRole) . '.');
    }

    /**
     * Get human-readable role label.
     */
    private function getRoleLabel(string $role): string
    {
        return match ($role) {
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_TEACHER => 'Staff',
            default => ucfirst($role),
        };
    }
}

