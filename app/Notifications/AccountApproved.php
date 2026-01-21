<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The plain text password generated for the user.
     */
    protected string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = url('/login');

        return (new MailMessage)
            ->subject('Account Approved - Silver Academy Family Portal')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your Silver Academy Family Portal account has been approved.')
            ->line('You can now log in to the portal using the following credentials:')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Password:** ' . $this->password)
            ->action('Log In to Portal', $loginUrl)
            ->line('For security reasons, we recommend changing your password after your first login.')
            ->line('If you have any questions, please contact the school office.')
            ->salutation('Warm regards, Silver Academy');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'account_approved',
        ];
    }
}
