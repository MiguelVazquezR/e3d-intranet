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
        $this->notifications = auth()->user()->notifications()->where('type', 'App\Notifications\RequestApproved')->get();
    }

    public function goToNotificationUrl($notification_id)
    {
        $notification = auth()->user()->notifications()->findOrFail($notification_id);
        $notification->markAsRead();
        redirect()->route($notification->data['url_name']);
    }

    public function render()
    {
        return view('livewire.notification.show-notifications-mobile');
    }
}
