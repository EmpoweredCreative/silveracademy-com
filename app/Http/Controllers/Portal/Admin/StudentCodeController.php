<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\ParentCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class StudentCodeController extends Controller
{
    /**
     * Get code status for a student.
     */
    public function show(Student $student): JsonResponse
    {
        $code = $student->activeAccessCode();
        if ($code === null) {
            return response()->json([
                'status' => null,
                'code_last4' => null,
                'max_links' => ParentCodeService::DEFAULT_MAX_LINKS,
                'current_link_count' => $student->currentLinkCount(),
                'created_at' => null,
                'expires_at' => null,
            ]);
        }

        return response()->json([
            'status' => $code->status,
            'code_last4' => $code->code_last4,
            'max_links' => $code->max_links,
            'current_link_count' => $student->currentLinkCount(),
            'created_at' => $code->created_at?->toIso8601String(),
            'expires_at' => $code->expires_at?->toIso8601String(),
        ]);
    }

    /**
     * Regenerate (revoke existing + create new) code for student.
     * When called via Inertia (browser), redirects back with flash so the code modal can show.
     */
    public function regenerate(Request $request, Student $student)
    {
        $result = ParentCodeService::createCodeForStudent($student, ParentCodeService::DEFAULT_MAX_LINKS, true);

        Log::info('Student access code regenerated', [
            'student_id' => $student->id,
            'admin_id' => auth()->id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'code' => $result['plain_code'],
                'max_links' => $result['access_code']->max_links,
                'expires_at' => $result['access_code']->expires_at?->toIso8601String(),
                'code_last4' => $result['access_code']->code_last4,
            ]);
        }

        $redirectUrl = $request->input('redirect_url', url()->previous());
        return redirect($redirectUrl)
            ->with('regenerated_code_plain', $result['plain_code'])
            ->with('regenerated_code_student_name', $student->name);
    }

    /**
     * Show confirmation page before regenerating (form POST, no JS required).
     */
    public function confirmRegenerate(Request $request, Student $student): Response
    {
        $redirectUrl = $request->input('redirect', route('admin.grades.show', $student->grade_id));
        return Inertia::render('Portal/Admin/Students/RegenerateCodeConfirm', [
            'student' => ['id' => $student->id, 'name' => $student->name],
            'regenerateUrl' => route('admin.students.code.regenerate', $student),
            'redirectUrl' => $redirectUrl,
            'csrf_token' => csrf_token(),
        ]);
    }

    /**
     * Update code settings (max_links, expires_at, status).
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        $code = $student->activeAccessCode();
        if ($code === null) {
            return response()->json(['message' => 'No active code for this student.'], 404);
        }

        $validated = $request->validate([
            'max_links' => 'sometimes|integer|min:1|max:50',
            'expires_at' => 'sometimes|nullable|date',
            'status' => 'sometimes|in:active,revoked',
        ]);

        if (array_key_exists('max_links', $validated)) {
            $code->max_links = $validated['max_links'];
        }
        if (array_key_exists('expires_at', $validated)) {
            $code->expires_at = $validated['expires_at'];
        }
        if (array_key_exists('status', $validated)) {
            $code->status = $validated['status'];
            if ($validated['status'] === 'revoked') {
                $code->revoked_at = now();
            }
        }
        $code->save();

        return response()->json([
            'status' => $code->status,
            'max_links' => $code->max_links,
            'expires_at' => $code->expires_at?->toIso8601String(),
        ]);
    }
}
