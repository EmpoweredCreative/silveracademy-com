<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): Response
    {
        $user = auth()->user();

        return Inertia::render('Portal/Settings', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar_url' => $user->avatar_url ?? null,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old avatar if exists
        if ($user->avatar_url) {
            $oldPath = public_path($user->avatar_url);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Store new avatar
        $file = $request->file('avatar');
        $filename = 'avatar-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/avatars'), $filename);

        $user->update([
            'avatar_url' => '/img/avatars/' . $filename,
        ]);

        return back()->with('success', 'Avatar updated successfully.');
    }

    /**
     * Remove the user's avatar.
     */
    public function removeAvatar()
    {
        $user = auth()->user();

        if ($user->avatar_url) {
            $oldPath = public_path($user->avatar_url);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $user->update(['avatar_url' => null]);
        }

        return back()->with('success', 'Avatar removed.');
    }
}
