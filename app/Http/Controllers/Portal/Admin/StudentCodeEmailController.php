<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Notifications\ParentCodeInvite;
use App\Services\ParentCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class StudentCodeEmailController extends Controller
{
    private const MAX_CODES_PER_EMAIL = 5;

    /**
     * Bulk send codes to all parents (contact emails) for students in this grade.
     * Groups by email; sends one email per address with up to 5 codes per email.
     * Uses SendGrid API.
     */
    public function bulkSendForGrade(Grade $grade): RedirectResponse
    {
        $apiKey = config('services.sendgrid.api_key') ?: env('SENDGRID_API_KEY');
        $apiKey = is_string($apiKey) ? trim($apiKey) : '';
        if ($apiKey === '') {
            Log::warning('StudentCodeEmailController::bulkSendForGrade: SendGrid API key missing', ['grade_id' => $grade->id]);
            return redirect()->back()->with('error', 'SendGrid is not configured. Set SENDGRID_API_KEY on the server and run: php artisan config:clear');
        }

        $students = $grade->students()
            ->with(['contactEmails', 'accessCodes' => fn ($q) => $q->active()])
            ->get();

        $byEmail = []; // email => [ ['student_name' => x, 'code' => y], ... ]
        foreach ($students as $student) {
            $code = $student->activeAccessCode();
            if (!$code) {
                continue;
            }
            $plain = $code->getDecryptedPlainCode();
            if ($plain === null) {
                continue;
            }
            foreach ($student->contactEmails as $contact) {
                $email = strtolower(trim($contact->email));
                if ($email === '') {
                    continue;
                }
                if (!isset($byEmail[$email])) {
                    $byEmail[$email] = [];
                }
                $byEmail[$email][] = ['student_name' => $student->name, 'code' => $plain];
            }
        }

        $sent = 0;
        foreach ($byEmail as $email => $codes) {
            $chunks = array_chunk($codes, self::MAX_CODES_PER_EMAIL);
            foreach ($chunks as $chunk) {
                if ((new ParentCodeInvite($email, null, $chunk))->send()) {
                    $sent++;
                }
            }
        }

        Log::info('Bulk code emails sent for grade', ['grade_id' => $grade->id, 'recipients' => $sent]);

        if ($sent === 0 && $byEmail !== []) {
            return redirect()->back()->with('error', 'No emails could be sent. Check server logs or SendGrid (from address must be verified).');
        }

        return redirect()->back()->with('success', "Code emails sent to {$sent} recipient(s).");
    }

    /**
     * Send this student's code to all their contact emails (or first linked parent if none).
     * Uses SendGrid API. If the code has no stored plain text (old code), we regenerate it then send.
     */
    public function sendToParent(Student $student): RedirectResponse
    {
        $apiKey = config('services.sendgrid.api_key') ?: env('SENDGRID_API_KEY');
        $apiKey = is_string($apiKey) ? trim($apiKey) : '';
        if ($apiKey === '') {
            Log::warning('StudentCodeEmailController::sendToParent: SendGrid API key missing', ['student_id' => $student->id]);
            return redirect()->back()->with('error', 'Email could not be sent: SendGrid is not configured. Set SENDGRID_API_KEY on the server and run: php artisan config:clear');
        }

        $recipients = $student->contactEmails()->pluck('email')->map(fn ($e) => strtolower(trim($e)))->filter()->unique()->values();
        if ($recipients->isEmpty()) {
            $firstParent = $student->parents()->first();
            if ($firstParent) {
                $recipients = collect([$firstParent->email]);
            }
        }
        if ($recipients->isEmpty()) {
            return redirect()->back()->with('error', 'No parent email to send to. Add parent emails in the import or link a parent first.');
        }

        $code = $student->activeAccessCode();
        if (! $code) {
            $result = ParentCodeService::createCodeForStudent($student, ParentCodeService::DEFAULT_MAX_LINKS, false);
            $code = $result['access_code'];
        }

        $plain = $code->getDecryptedPlainCode();
        if ($plain === null) {
            // Old code without stored plain text: regenerate so we can email it
            $result = ParentCodeService::createCodeForStudent($student, ParentCodeService::DEFAULT_MAX_LINKS, true);
            $plain = $result['plain_code'];
        }

        Log::info('StudentCodeEmailController::sendToParent: sending via SendGrid', [
            'student_id' => $student->id,
            'recipient_count' => $recipients->count(),
        ]);

        $payload = [['student_name' => $student->name, 'code' => $plain]];
        $sent = 0;
        foreach ($recipients as $email) {
            if ((new ParentCodeInvite($email, null, $payload))->send()) {
                $sent++;
            }
        }

        if ($sent === 0) {
            Log::error('StudentCodeEmailController::sendToParent: SendGrid did not accept the send', ['student_id' => $student->id]);
            return redirect()->back()->with('error', 'Email could not be sent. Check server logs and SendGrid (verify sender/from address in SendGrid).');
        }

        return redirect()->back()->with('success', 'Code email sent to ' . $sent . ' recipient(s).');
    }
}
