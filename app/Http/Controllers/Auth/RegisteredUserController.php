<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\RegistrationReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Registration is for PARENTS ONLY. Staff are imported via admin.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     * Registration is for PARENTS ONLY - no password required.
     * Password will be generated when admin approves the account.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'is_parent_confirmed' => 'required|accepted',
            'terms_accepted' => 'required|accepted',
        ], [
            'is_parent_confirmed.accepted' => 'You must confirm that you are a parent of a student at Silver Academy.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions to register.',
        ]);

        // Create parent user without password - password will be generated on approval
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => null, // No password until approved
            'role' => User::ROLE_PARENT,
            'is_approved' => false,
        ]);

        // Send registration received notification
        $user->notify(new RegistrationReceived());

        // Redirect to pending approval page (do NOT log them in)
        return redirect()->route('pending-approval');
    }

    /**
     * Display the pending approval page.
     */
    public function pendingApproval(): Response
    {
        return Inertia::render('Auth/PendingApproval');
    }
}




