<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $this->notifiable
     * @return array
     */
    public function via($this->notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $this->notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($this->notifiable)
    {
        return (new MailMessage)
            ->line("Hello " . $this->user->name)
            ->line("Your store created successfully")
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $this->notifiable
     * @return array
     */
    public function toArray($this->notifiable)
    {
        return [
            //
        ];
    }
}
