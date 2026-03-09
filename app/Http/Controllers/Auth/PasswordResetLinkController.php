<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
    /**
     * Display the form to request a password reset link.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Send a reset link to the given user.
     * Returns Inertia page with status in response (no redirect) so the success message
     * shows reliably on production without depending on session flash.
     */
    public function store(Request $request): RedirectResponse|Response
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (\Throwable $e) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not send the reset link. Please check that mail is configured and try again.']);
        }

        if ($status === Password::RESET_LINK_SENT) {
            return Inertia::render('Auth/ForgotPassword', [
                'status' => __($status),
            ]);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
