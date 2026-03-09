<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     */
    public string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     * Sends via SendGrid so password reset uses the same mail channel as the rest of the app.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $this->sendViaSendGrid($notifiable);
        return [];
    }

    /**
     * Send the password reset email via SendGrid API.
     */
    protected function sendViaSendGrid(object $notifiable): void
    {
        $apiKey = config('services.sendgrid.api_key') ?: env('SENDGRID_API_KEY');
        if (!$apiKey) {
            Log::warning('ResetPassword: SendGrid not configured, password reset email not sent', [
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
            return;
        }

        try {
            $email = $notifiable->getEmailForPasswordReset();
            $resetUrl = url(route('password.reset', [
                'token' => $this->token,
                'email' => $email,
            ]));

            $sendgrid = new SendGrid($apiKey);
            $mail = new Mail();
            $mail->setFrom(
                config('services.sendgrid.from_email'),
                config('services.sendgrid.from_name', 'Silver Academy')
            );
            $mail->setSubject('Reset Password - Silver Academy Family Portal');
            $mail->addTo($email, $notifiable->name ?? $email);

            $plain = "You are receiving this email because we received a password reset request for your account.\n\n";
            $plain .= "Click the link below to reset your password:\n\n";
            $plain .= $resetUrl . "\n\n";
            $plain .= "This link will expire in " . (Config::get('auth.passwords.users.expire', 60)) . " minutes.\n\n";
            $plain .= "If you did not request a password reset, no further action is required.\n\n";
            $plain .= "— Silver Academy";

            $mail->addContent('text/plain', $plain);

            $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%); padding: 30px; text-align: center;'>
                    <h1 style='color: white; margin: 0; font-size: 24px;'>Silver Academy Family Portal</h1>
                </div>
                <div style='padding: 30px; background: #ffffff;'>
                    <h2 style='color: #1e3a5f; margin-top: 0;'>Reset your password</h2>
                    <p style='color: #333; font-size: 16px; line-height: 1.6;'>
                        You requested a password reset. Click the button below to choose a new password:
                    </p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='" . htmlspecialchars($resetUrl) . "' style='display: inline-block; background: #1e3a5f; color: white; text-decoration: none; padding: 14px 30px; border-radius: 6px; font-weight: bold; font-size: 16px;'>Reset password</a>
                    </div>
                    <p style='color: #666; font-size: 14px; line-height: 1.6;'>
                        This link expires in " . (int) (Config::get('auth.passwords.users.expire', 60)) . " minutes.
                    </p>
                    <p style='color: #666; font-size: 14px; line-height: 1.6;'>
                        If you didn't request this, you can ignore this email.
                    </p>
                </div>
                <div style='background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;'>
                    <p style='color: #666; font-size: 14px; margin: 0;'>— Silver Academy</p>
                </div>
            </div>";
            $mail->addContent('text/html', $html);

            $response = $sendgrid->send($mail);

            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                Log::info('Password reset email sent via SendGrid', ['email' => $email]);
            } else {
                Log::error('SendGrid error sending password reset email', [
                    'email' => $email,
                    'status_code' => $response->statusCode(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('ResetPassword notification failed', [
                'email' => $notifiable->getEmailForPasswordReset(),
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
