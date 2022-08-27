<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StartedOrderNotification extends Notification
{
    use Queueable;

    public $request_name,
        $request_id,
        $url_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request_name, $request_id, $url_name)
    {
        $this->request_name = $request_name;
        $this->request_id = $request_id;
        $this->url_name = $url_name;
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
            'url_name' => $this->url_name,
            'message' => "Se ha iniciado tu <b>{$this->request_name}</b> con id <b>{$this->request_id}</b>",
        ];
    }
}
