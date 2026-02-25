<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ParentCodeWelcome;
use App\Services\ParentCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentCodeController extends Controller
{
    /**
     * Validate a parent code (public). Returns minimal student hint if valid.
     */
    public function validateCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:32',
        ]);

        $result = ParentCodeService::validateCode($request->input('code'));
        if ($result === null) {
            return response()->json(['valid' => false]);
        }

        $student = $result['student'];
        $accessCode = $result['access_code'];

        if (!$accessCode->isValid()) {
            return response()->json(['valid' => false]);
        }

        return response()->json([
            'valid' => true,
            'student_hint' => ParentCodeService::studentHint($student),
        ]);
    }

    /**
     * First-time signup with email + code. Creates account if needed, links to student, sends password email for new accounts.
     */
    public function signup(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|lowercase|email|max:255',
            'code' => 'required|string|max:32',
        ]);

        $email = $request->input('email');
        $code = $request->input('code');

        $result = ParentCodeService::validateCode($code);
        if ($result === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid code or this student has reached the maximum number of linked accounts. Contact the school office.',
            ], 422);
        }

        $student = $result['student'];
        $accessCode = $result['access_code'];

        if (!$accessCode->isValid()) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid code or this student has reached the maximum number of linked accounts. Contact the school office.',
            ], 422);
        }

        $user = User::where('email', $email)->first();
        $isNewUser = $user === null;

        $sendWelcomeEmail = false;
        $plainPassword = null;

        DB::transaction(function () use ($email, $student, $accessCode, &$user, &$isNewUser, &$sendWelcomeEmail, &$plainPassword) {
            if ($user === null) {
                $plainPassword = Str::password(12);
                $user = User::create([
                    'name' => explode('@', $email)[0],
                    'email' => $email,
                    'password' => Hash::make($plainPassword),
                    'role' => User::ROLE_PARENT,
                    'is_approved' => true,
                    'approved_at' => now(),
                    'approved_by' => null,
                ]);
                $sendWelcomeEmail = true;
            } else {
                if ($user->role !== User::ROLE_PARENT) {
                    $user->update(['role' => User::ROLE_PARENT]);
                }
                if (!$user->isApproved()) {
                    $plainPassword = Str::password(12);
                    $user->update([
                        'password' => Hash::make($plainPassword),
                        'is_approved' => true,
                        'approved_at' => now(),
                        'approved_by' => null,
                    ]);
                    $sendWelcomeEmail = true;
                }
            }

            if (!$user->children()->where('students.id', $student->id)->exists()) {
                $user->children()->attach($student->id);
            }
        });

        if ($sendWelcomeEmail && $plainPassword !== null) {
            $user->notify(new ParentCodeWelcome($plainPassword));
        }

        return response()->json([
            'ok' => true,
            'message' => $isNewUser || $sendWelcomeEmail
                ? 'Check your email for your password and login link.'
                : 'Child added successfully.',
        ]);
    }
}
