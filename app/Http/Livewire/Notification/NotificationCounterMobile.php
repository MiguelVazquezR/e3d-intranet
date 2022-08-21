<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;

class NotificationCounterMobile extends Component
{
    public $unread;

    protected $listeners = [
        'notification-counter-refresh' => 'resetCounter',
    ];

    public function mount()
    {
        $this->resetCounter();
    }

    public function resetCounter()
    {
        $this->unread = auth()->user()->unreadNotifications()->where('type', '!=','App\Notifications\MessageSent')->get('id')->count();
    }

    public function render()
    {
        return view('livewire.notification.notification-counter-mobile');
    }
}
