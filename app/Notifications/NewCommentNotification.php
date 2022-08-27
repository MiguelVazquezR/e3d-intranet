<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $user_name,
        $message_id,
        $message_subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name, $message_id, $message_subject)
    {
        $this->user_name = $user_name;
        $this->message_id = $message_id;
        $this->message_subject = $message_subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'url_name' => '',
            'message' => "<b>{$this->user_name}</b> ha hecho un comentario en tu mensaje con asunto: <b>{$this->message_subject}</b>",
            'message_id' => $this->message_id,
        ];
    }
}
