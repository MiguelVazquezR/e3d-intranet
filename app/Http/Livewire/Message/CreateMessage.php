<?php

namespace App\Http\Livewire\Message;

use App\Models\Message;
use App\Models\MessageUsers;
use App\Models\User;
use App\Notifications\MessageSent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateMessage extends Component
{
    public $open = false,
        $subject,
        $body,
        $user_id,
        $user_list = [];

    protected $rules = [
        'subject' => 'required',
        'body' => 'required',
        'user_list' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function updatedUserId($user_id)
    {
        if($user_id === 'all') {
            $this->user_list = User::where('active', 1)->where('id', '!=', Auth::user()->id)->pluck('id')->all();
        }elseif ( !in_array($user_id, $this->user_list) ) {
            $this->user_list[] = $user_id;
        }
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function removeUser($index)
    {
        unset($this->user_list[$index]);
    }

    public function send()
    {
        $validated_data = $this->validate(null, [
            'user_list.required' => 'Debe de haber mínimo un usuario seleccionado para enviar el mensaje'
        ]);
        
        // create message
        $message = Message::create($validated_data + [
            'from_user_id' => Auth::user()->id,
        ]);

        foreach ($this->user_list as $user_id) {
            $message_user = MessageUsers::create([
                'message_id' => $message->id,
                'user_id' => $user_id,
            ]);

            User::findOrFail($user_id)->notify( new MessageSent($message) );
        }
        
        $this->reset();

        $this->emit('success', 'Mensaje enviado');
    }

    public function render()
    {
        return view('livewire.message.create-message', [
            'users' => User::where('active', 1)->where('id', '!=', Auth::user()->id)->get(),
        ]);
    }
}
