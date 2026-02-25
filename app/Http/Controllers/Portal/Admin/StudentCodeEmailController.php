<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Notifications\ParentCodeInvite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class StudentCodeEmailController extends Controller
{
    private const MAX_CODES_PER_EMAIL = 5;

    /**
     * Bulk send codes to all parents (contact emails) for students in this grade.
     * Groups by email; sends one email per address with up to 5 codes per email.
     */
    public function bulkSendForGrade(Grade $grade): RedirectResponse
    {
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
                (new ParentCodeInvite($email, null, $chunk))->send();
                $sent++;
            }
        }

        Log::info('Bulk code emails sent for grade', ['grade_id' => $grade->id, 'recipients' => $sent]);

        return redirect()->back()->with('success', "Code emails sent to {$sent} recipient(s).");
    }

    /**
     * Send this student's code to all their contact emails (or first linked parent if none).
     */
    public function sendToParent(Student $student): RedirectResponse
    {
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
        if (!$code) {
            return redirect()->back()->with('error', 'No active code for this student. Generate a code first.');
        }
        $plain = $code->getDecryptedPlainCode();
        if ($plain === null) {
            return redirect()->back()->with('error', 'Code cannot be sent by email. Regenerate the code first.');
        }

        $payload = [['student_name' => $student->name, 'code' => $plain]];
        foreach ($recipients as $email) {
            (new ParentCodeInvite($email, null, $payload))->send();
        }

        return redirect()->back()->with('success', 'Code email sent to ' . $recipients->count() . ' recipient(s).');
    }
}
