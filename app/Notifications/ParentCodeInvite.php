<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Log;
use SendGrid\Mail\Mail;
use SendGrid;

class ParentCodeInvite
{
    protected string $toEmail;

    protected ?string $toName;

    /** @var array<int, array{student_name: string, code: string}> */
    protected array $codes;

    /**
     * @param  array<int, array{student_name: string, code: string}>  $codes
     */
    public function __construct(string $toEmail, ?string $toName, array $codes)
    {
        $this->toEmail = $toEmail;
        $this->toName = $toName;
        $this->codes = $codes;
    }

    /**
     * Send the invite email via SendGrid API.
     * Returns true if the email was sent successfully, false otherwise.
     * Uses config first; falls back to env() so SendGrid is used even if config was cached before the key was set.
     */
    public function send(): bool
    {
        $apiKey = config('services.sendgrid.api_key') ?: env('SENDGRID_API_KEY');
        $apiKey = is_string($apiKey) ? trim($apiKey) : '';
        if ($apiKey === '') {
            Log::warning('ParentCodeInvite: SendGrid API key missing (set SENDGRID_API_KEY and run php artisan config:clear)', [
                'to_email' => $this->toEmail,
                'codes_count' => count($this->codes),
            ]);
            return false;
        }

        Log::info('ParentCodeInvite: calling SendGrid API', ['to_email' => $this->toEmail]);

        $portalUrl = url('/login');
        $displayName = $this->toName ?: 'Parent';

        $codesBlurb = $this->buildCodesBlurb();
        $multiChildNote = 'If you have more than one child at the school, you may receive separate emails with a code for each. After logging in, you can link each child in Settings → Linked Students by entering each code and clicking Add child.';

        $plain = "Hello {$displayName}!\n\n";
        $plain .= "Your Parent Code(s) for the Silver Academy Family Portal:\n\n";
        $plain .= $codesBlurb;
        $plain .= "\nUse the code(s) at {$portalUrl} to sign up or link your child (after logging in: Settings → Linked Students, enter the code and click Add child).\n\n";
        $plain .= "{$multiChildNote}\n\n";
        $plain .= "If you have any questions, please contact the school office.\n\n";
        $plain .= "Warm regards,\nSilver Academy";

        $htmlCodes = $this->buildHtmlCodesBlock();
        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%); padding: 30px; text-align: center;'>
                <h1 style='color: white; margin: 0; font-size: 24px;'>Silver Academy Family Portal</h1>
            </div>
            <div style='padding: 30px; background: #ffffff;'>
                <h2 style='color: #1e3a5f; margin-top: 0;'>Hello " . htmlspecialchars($displayName) . "!</h2>
                <p style='color: #333; font-size: 16px; line-height: 1.6;'>Your Parent Code(s) for the Family Portal:</p>
                {$htmlCodes}
                <p style='color: #333; font-size: 14px; line-height: 1.6;'>Use the code(s) at <a href='{$portalUrl}'>{$portalUrl}</a> to sign up or link your child (after logging in: <strong>Settings → Linked Students</strong>, enter the code and click <strong>Add child</strong>).</p>
                <p style='color: #666; font-size: 14px; line-height: 1.6;'>{$multiChildNote}</p>
                <p style='color: #666; font-size: 14px; line-height: 1.6;'>If you have any questions, please contact the school office.</p>
            </div>
            <div style='background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;'>
                <p style='color: #666; font-size: 14px; margin: 0;'>Warm regards,<br><strong style='color: #1e3a5f;'>Silver Academy</strong></p>
            </div>
        </div>";

        try {
            $fromEmail = config('services.sendgrid.from_email') ?: env('SENDGRID_FROM_EMAIL', 'noreply@silveracademypa.org');
            $fromName = config('services.sendgrid.child_portal_from_name') ?: env('SENDGRID_CHILD_PORTAL_FROM_NAME', 'Silver Academy Child Portal');

            $sendgrid = new SendGrid($apiKey);
            $email = new Mail();
            $email->setFrom($fromEmail, $fromName);
            $email->setSubject(count($this->codes) > 1 ? 'Your Parent Codes – Silver Academy' : 'Your Parent Code – Silver Academy');
            $email->addTo($this->toEmail, $this->toName ?? '');
            $email->addContent('text/plain', $plain);
            $email->addContent('text/html', $html);

            $response = $sendgrid->send($email);
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                Log::info('ParentCodeInvite sent', ['email' => $this->toEmail, 'count' => count($this->codes)]);
                return true;
            }
            Log::error('SendGrid error ParentCodeInvite', ['email' => $this->toEmail, 'status' => $response->statusCode(), 'body' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            Log::error('ParentCodeInvite send failed', ['email' => $this->toEmail, 'message' => $e->getMessage()]);
            return false;
        }
    }

    protected function buildCodesBlurb(): string
    {
        $lines = [];
        foreach ($this->codes as $item) {
            $lines[] = $item['student_name'] . ': ' . $item['code'];
        }
        return implode("\n", $lines);
    }

    protected function buildHtmlCodesBlock(): string
    {
        $rows = '';
        foreach ($this->codes as $item) {
            $name = htmlspecialchars($item['student_name']);
            $code = htmlspecialchars($item['code']);
            $rows .= "<p style='margin: 8px 0; color: #333;'><strong>{$name}:</strong> <code style='background: #e9ecef; padding: 4px 8px; border-radius: 4px; font-size: 14px;'>{$code}</code></p>";
        }
        return "<div style='background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0;'>{$rows}</div>";
    }
}
