<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;

class ShowUser extends Component
{
    public $user,
        $open = false;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->user = new User();
    }

    public function openModal(User $user)
    {
        $this->user = $user;
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.user.show-user');
    }
}
