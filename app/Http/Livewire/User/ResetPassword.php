<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;

class ResetPassword extends Component
{
    public
        $open = false,
        $password,
        $user;

    protected $rules = [
        'password' => 'required',
    ];

    protected $listeners = [
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

    public function openModal(User $user)
    {
        $this->open = true;
        $this->user = $user;
    }

    public function resetPassword()
    {
        $this->validate();
        $this->user->password = bcrypt($this->password);
        $this->user->save();

        $this->reset();

        $this->emit('success', 'Contraseña reseteada');
    }

    public function render()
    {
        return view('livewire.user.reset-password');
    }
}
