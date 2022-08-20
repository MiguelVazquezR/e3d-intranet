<?php

namespace App\Http\Livewire\Message;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MessageCounterMobile extends Component
{
    public
        $unreaded;

    protected $listeners = [
        'messages-counter-refresh' => 'resetCounter',
    ];

    public function mount()
    {
        $this->resetCounter();
    }

    public function resetCounter()
    {
        $this->emitTo('message.show-messages-mobile', 'messages-counter-refresh');
        $this->unreaded = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\MessageSent')->get('id')->count();
    }

    public function render()
    {
        return view('livewire.message.message-counter-mobile');
    }
}
