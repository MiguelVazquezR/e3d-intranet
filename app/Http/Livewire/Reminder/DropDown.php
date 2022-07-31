<?php

namespace App\Http\Livewire\Reminder;

use Livewire\Component;

class DropDown extends Component
{
    protected $listeners = [
        'render'
    ];

    public function createReminder()
    {
        $this->emitTo('reminder.create-reminder', 'openModal');
    }

    public function render()
    {
        $reminders = auth()->user()->reminders;

        return view('livewire.reminder.drop-down', compact('reminders'));
    }
}
