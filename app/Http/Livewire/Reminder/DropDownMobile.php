<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use Livewire\Component;

class DropDownMobile extends Component
{
    public $notify = false;

    protected $listeners = [
        'showNotification'
    ];

    public function showNotification()
    {
        $this->notify = !empty(Reminder::where('remind_at', '<', now())
        ->where('user_id', auth()->user()->id)
        ->pluck('id')
        ->all());
    }

    public function render()
    {
        $this->showNotification();

        return view('livewire.reminder.drop-down-mobile');
    }
}
