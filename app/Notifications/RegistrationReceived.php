<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationReceived extends Notification implements ShouldQueue
{
    use Queueable;

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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Registration Received - Silver Academy')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering for the Silver Academy Family Portal.')
            ->line('Your registration has been received and is currently awaiting approval from the school administration.')
            ->line('Once your account is approved, you will receive another email with your login credentials.')
            ->line('If you have any questions in the meantime, please contact the school office.')
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
            'type' => 'registration_received',
        ];
    }
}
