<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Admins and super admins always bypass approval checks
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        if (!$request->user()->isApproved()) {
            // Log them out and redirect to pending approval page
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('pending-approval')
                ->with('error', 'Your account is pending approval.');
        }

        return $next($request);
    }
}
