<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'staffEmailDomain' => config('app.staff_email_domain'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => 'required|in:parent,staff',
        ]);

        // Determine role based on account type selection
        $role = User::ROLE_PARENT;

        if ($request->account_type === 'staff') {
            $staffDomain = config('app.staff_email_domain', 'silveracademypa.org');
            $emailDomain = substr(strrchr($request->email, "@"), 1);

            if (strtolower($emailDomain) !== strtolower($staffDomain)) {
                return back()->withErrors([
                    'email' => 'Staff registration requires a @' . $staffDomain . ' email address. If you are a parent, please select "I am a Parent" instead.',
                ])->withInput();
            }

            $role = User::ROLE_TEACHER;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('portal.dashboard', absolute: false));
    }
}




