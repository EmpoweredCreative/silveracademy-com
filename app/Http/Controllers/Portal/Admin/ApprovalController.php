<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AccountApproved;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApprovalController extends Controller
{
    /**
     * Display a listing of pending approvals (parents only).
     */
    public function index(): Response
    {
        $pendingUsers = User::where('role', User::ROLE_PARENT)
            ->pendingApproval()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'created_at_formatted' => $user->created_at->format('M j, Y g:i A'),
                ];
            });

        $counts = [
            'pending' => $pendingUsers->count(),
            'total_parents' => User::where('role', User::ROLE_PARENT)->count(),
            'approved_parents' => User::where('role', User::ROLE_PARENT)->approved()->count(),
        ];

        return Inertia::render('Portal/Admin/Approvals/Index', [
            'pendingUsers' => $pendingUsers,
            'counts' => $counts,
        ]);
    }

    /**
     * Display the specified pending user.
     */
    public function show(User $user): Response
    {
        // Ensure we're viewing a pending parent
        if ($user->role !== User::ROLE_PARENT || $user->isApproved()) {
            abort(404);
        }

        // Get all students for linking
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

        return Inertia::render('Portal/Admin/Approvals/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'created_at_formatted' => $user->created_at->format('M j, Y g:i A'),
            ],
            'students' => $students,
        ]);
    }

    /**
     * Approve a pending user.
     */
    public function approve(Request $request, User $user)
    {
        // Ensure we're approving a pending parent
        if ($user->role !== User::ROLE_PARENT || $user->isApproved()) {
            return back()->with('error', 'This user is already approved or is not a parent.');
        }

        $validated = $request->validate([
            'children_ids' => 'nullable|array',
            'children_ids.*' => 'exists:students,id',
        ]);

        // Approve the user and generate password
        $password = $user->approve(auth()->user());

        // Link children if provided
        if (!empty($validated['children_ids'])) {
            $user->children()->sync($validated['children_ids']);
        }

        // Send approval notification with password
        $user->notify(new AccountApproved($password));

        return redirect()->route('admin.approvals.index')
            ->with('success', 'User approved successfully. Login credentials have been sent to their email.');
    }

    /**
     * Reject a pending user (delete their account).
     */
    public function reject(User $user)
    {
        // Ensure we're rejecting a pending parent
        if ($user->role !== User::ROLE_PARENT || $user->isApproved()) {
            return back()->with('error', 'This user is already approved or is not a parent.');
        }

        $user->reject();

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Registration rejected and removed.');
    }

    /**
     * Bulk approve multiple pending users.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $approvedCount = 0;

        foreach ($validated['user_ids'] as $userId) {
            $user = User::find($userId);
            
            if ($user && $user->role === User::ROLE_PARENT && !$user->isApproved()) {
                $password = $user->approve(auth()->user());
                $user->notify(new AccountApproved($password));
                $approvedCount++;
            }
        }

        return redirect()->route('admin.approvals.index')
            ->with('success', "{$approvedCount} user(s) approved successfully. Login credentials have been sent to their emails.");
    }
}
