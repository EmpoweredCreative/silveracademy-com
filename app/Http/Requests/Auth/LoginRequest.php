<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Check if user exists and is approved before attempting auth
        $user = User::where('email', $this->email)->first();

        if ($user) {
            // Admins and super admins always bypass approval checks
            $isAdmin = in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);

            if (!$isAdmin) {
                // Check if user has no password (awaiting approval)
                if (empty($user->password)) {
                    RateLimiter::hit($this->throttleKey());

                    throw ValidationException::withMessages([
                        'email' => 'Your account is pending approval. You will receive an email with your login credentials once approved.',
                    ]);
                }

                // Check if user is not approved (only if the column exists)
                try {
                    if (method_exists($user, 'isApproved') && !$user->isApproved()) {
                        RateLimiter::hit($this->throttleKey());

                        throw ValidationException::withMessages([
                            'email' => 'Your account is pending approval. Please wait for an administrator to approve your registration.',
                        ]);
                    }
                } catch (\Exception $e) {
                    // Column might not exist yet - allow login
                }
            }
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}






