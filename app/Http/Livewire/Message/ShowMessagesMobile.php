<?php

namespace App\Http\Livewire\Message;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowMessagesMobile extends Component
{
    public $notifications,
        $sent_messages,
        $received = true;

    protected $listeners = [
        'messages-counter-refresh' => 'resetCounter',
        'show-message-from-notification' => 'showMessageFromNotification',
    ];

    public function mount()
    {
        $this->resetCounter();
    }

    public function resetCounter()
    {
        $this->notifications = Auth::user()->notifications()->where('type', 'App\Notifications\MessageSent')->get();
        $this->sent_messages = Message::where('from_user_id', Auth::user()->id)->latest()->get();
        $this->emitTo('message.message-counter-mobile', 'messages-counter-refresh');
    }

    public function createMessage()
    {
        $this->emitTo('message.create-message', 'openModal');
    }

    public function showMessage($id)
    {
        if ($this->received) {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $message = Message::find($notification->data["id"]);

            $notification->markAsRead();
            $receiver = $message->pivot->where('user_id', Auth::user()->id)->first();
            $receiver->markAsRead();
        } else {
            $message = Message::find($id);
        }

        $this->resetCounter();
        $this->emitTo('message.show-message', 'openModal', $message);
    }

    public function showMessageFromNotification($message_id)
    {
        $message = Message::findOrFail($message_id);
        $this->emitTo('message.show-message', 'openModal', $message);
    }

    public function render()
    {
        return view('livewire.message.show-messages-mobile');
    }
}
