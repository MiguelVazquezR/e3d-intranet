<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;

class ShowNotificationsMobile extends Component
{
    public $notifications;

    protected $listeners = [
        'notification-counter-refresh' => 'resetCounter',
    ];

    public function mount()
    {
        $this->resetCounter();
    }

    public function resetCounter()
    {
        $this->notifications = auth()->user()->notifications()->where('type', '!=','App\Notifications\MessageSent')->get();
    }

    public function goToNotificationUrl($notification_id)
    {
        $notification = auth()->user()->notifications()->findOrFail($notification_id);
        $notification->markAsRead();
        
        if($notification->data['url_name']) {
            redirect()->route($notification->data['url_name']);
        }else {
            $this->emit('show-message-from-notification', $notification->data['message_id']);
        }

        $this->emitTo('notification.notification-counter-mobile', 'notification-counter-refresh');
        $this->resetCounter();
    }

    public function render()
    {
        return view('livewire.notification.show-notifications-mobile');
    }
}
