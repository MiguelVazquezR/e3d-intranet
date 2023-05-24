<?php

namespace App\Notifications;

use App\Models\Machine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceRequiredNotificaton extends Notification
{
    use Queueable;

    public $machine;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Machine $machine)
    {
        $this->machine = $machine;
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

    public function toDatabase($notifiable)
    {
        return [
            'url_name' => 'machines',
            'message' => "La máquina <b>{$this->machine->name}</b> necesita mantenimiento preventivo. Avisa al personal de mantenimiento",
        ];
    }
}
