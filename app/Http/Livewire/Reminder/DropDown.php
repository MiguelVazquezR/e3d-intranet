<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use Livewire\Component;

class DropDown extends Component
{
    public $notify = false;

    protected $listeners = [
        'render',
        'delete',
        'showNotification'
    ];

    public function createReminder()
    {
        $this->emitTo('reminder.create-reminder', 'openModal');
    }

    public function delete($reminder_id)
    {
        Reminder::find($reminder_id)->delete();
        $this->emit('success', 'Recordatorio removido');
        $this->showNotification();
    }

    public function showNotification()
    {
        $this->notify = !empty(Reminder::where('remind_at', '<', now())
            ->where('user_id', auth()->user()->id)
            ->pluck('id')
            ->all());
    }

    public function render()
    {
        $reminders = auth()->user()->reminders;
        $this->showNotification();

        return view('livewire.reminder.drop-down', compact('reminders'));
    }
}
