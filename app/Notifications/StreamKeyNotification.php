<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StreamKeyNotification extends Notification
{
    use Queueable;
    public $user;
    public $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
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
            ->subject('Your Content Token')
            ->line('Thank you for completing your payments for Somali Nite Live Event.')
            ->line('Your stream token is: ' . $this->subscription->stream_token . '. Click https://live.baze.co.ke/somalinite and use the streaming key')
            ->line('to access and watch the Event.')
            ->line('How to use your Streaming Key:')
            ->line('1. Navigate to the Somali Nite homepage https://live.baze.co.ke/somalinite')
            ->line('2. Enter the Streaming Token received.')
            ->line('3. You will automatically be redirected to watch the event.')
            ->line('If you have any questions or need assistance, please don’t hesitate to reach out to our support team.')
            ->line('Customer Care on E-mail: customercare@safaricom.co.ke.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'stream_token' => $this->subscription->stream_token
        ];
    }
}
