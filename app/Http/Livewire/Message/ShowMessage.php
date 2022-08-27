<?php

namespace App\Http\Livewire\Message;

use App\Models\Comment;
use App\Models\Message;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowMessage extends Component
{
    public $open = false,
        $message,
        $comment_body,
        $receivers = [],
        $users_read = [],
        $users_unread = [],
        $comments = [];

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->message = new Message();
    }

    public function sendComment()
    {
        Comment::create([
            'body' => $this->comment_body,
            'user_id' => Auth::user()->id,
            'message_id' => $this->message->id,
        ]);

        //set notifications as unreaded
        $this->message->MarkAsUnreadNotifications();

        //set message as unreaded
        $receivers = $this->message->pivot;
        foreach ($receivers as $receiver) {
            $receiver->markAsUnread();
        }

        // notify message's creator 
        if( $this->message->creator->id != auth()->user()->id ) {
            $this->message->creator->notify( new NewCommentNotification(auth()->user()->name, $this->message->id, $this->message->subject) );
        }

        $this->updateView();
        $this->emit('success', 'Comentario agregado');
    }

    public function updateView()
    {
        $this->reset('comment_body');
        $this->message = Message::find($this->message->id);
        $this->receivers = $this->message->pivot;
        
        // save read and unread users for current message
        $this->users_read = [];
        $this->users_unread = [];
        foreach ($this->receivers as $receiver) {
            if ($receiver->readed_at)
                $this->users_read[] = $receiver->user;
            else
                $this->users_unread[] = $receiver->user;
        }
    }

    public function openModal(Message $message)
    {
        $this->message = $message;
        $this->receivers = $message->pivot;
        
        // save read and unread users for current message
        $this->users_read = [];
        $this->users_unread = [];
        foreach ($this->receivers as $receiver) {
            if ($receiver->readed_at)
                $this->users_read[] = $receiver->user;
            else
                $this->users_unread[] = $receiver->user;
        }

        $this->open = true;
    }

    public function render()
    {
        $this->comments = $this->message->comments;
        return view('livewire.message.show-message');
    }
}
