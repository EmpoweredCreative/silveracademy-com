<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use SendGrid\Mail\Mail;
use SendGrid;

class RegistrationReceived extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Send via SendGrid directly
        $this->sendViaSendGrid($notifiable);
        
        // Return empty array since we're handling it ourselves
        return [];
    }

    /**
     * Send the email via SendGrid API.
     */
    protected function sendViaSendGrid(object $notifiable): void
    {
        try {
            $sendgrid = new SendGrid(config('services.sendgrid.api_key'));
            
            $email = new Mail();
            $email->setFrom(
                config('services.sendgrid.from_email'),
                config('services.sendgrid.from_name', 'Silver Academy')
            );
            $email->setSubject('Registration Received - Silver Academy Family Portal');
            $email->addTo($notifiable->email, $notifiable->name);
            
            // Plain text version
            $content = "Hello {$notifiable->name}!\n\n";
            $content .= "Thank you for registering for the Silver Academy Family Portal.\n\n";
            $content .= "Your registration has been received and is currently awaiting approval from the school administration.\n\n";
            $content .= "Once your account is approved, you will receive another email with your login credentials.\n\n";
            $content .= "If you have any questions in the meantime, please contact the school office.\n\n";
            $content .= "Warm regards,\nSilver Academy";
            
            $email->addContent("text/plain", $content);
            
            // HTML version
            $htmlContent = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%); padding: 30px; text-align: center;'>
                    <h1 style='color: white; margin: 0; font-size: 24px;'>Silver Academy Family Portal</h1>
                </div>
                <div style='padding: 30px; background: #ffffff;'>
                    <h2 style='color: #1e3a5f; margin-top: 0;'>Hello {$notifiable->name}!</h2>
                    <p style='color: #333; font-size: 16px; line-height: 1.6;'>
                        Thank you for registering for the Silver Academy Family Portal.
                    </p>
                    <div style='background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; border-left: 4px solid #f5a623;'>
                        <p style='margin: 0; color: #333; font-size: 16px;'>
                            <strong>What happens next?</strong><br><br>
                            Your registration has been received and is currently awaiting approval from the school administration.
                        </p>
                    </div>
                    <p style='color: #333; font-size: 16px; line-height: 1.6;'>
                        Once your account is approved, you will receive another email with your login credentials.
                    </p>
                    <p style='color: #666; font-size: 14px; line-height: 1.6;'>
                        If you have any questions in the meantime, please contact the school office.
                    </p>
                </div>
                <div style='background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;'>
                    <p style='color: #666; font-size: 14px; margin: 0;'>
                        Warm regards,<br>
                        <strong style='color: #1e3a5f;'>Silver Academy</strong>
                    </p>
                </div>
            </div>";
            
            $email->addContent("text/html", $htmlContent);
            
            $response = $sendgrid->send($email);
            
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                Log::info('Registration received email sent', [
                    'user_id' => $notifiable->id,
                    'email' => $notifiable->email,
                ]);
            } else {
                Log::error('SendGrid error sending registration received email', [
                    'user_id' => $notifiable->id,
                    'email' => $notifiable->email,
                    'status_code' => $response->statusCode(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending registration received email', [
                'user_id' => $notifiable->id,
                'email' => $notifiable->email,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'registration_received',
        ];
    }
}
