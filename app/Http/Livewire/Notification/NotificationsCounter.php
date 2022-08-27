<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;

class NotificationsCounter extends Component
{
    public $notifications,
        $unread;

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
        $this->unread = auth()->user()->unreadNotifications()->where('type', '!=','App\Notifications\MessageSent')->get('id')->count();
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

        $this->resetCounter();
    }

    public function render()
    {
        return view('livewire.notification.notifications-counter');
    }
}
