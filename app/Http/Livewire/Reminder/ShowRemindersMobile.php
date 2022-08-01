<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use Livewire\Component;

class ShowRemindersMobile extends Component
{
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
        $this->emitTo('reminder.drop-down-mobile', 'showNotification');
    }

    public function render()
    {
        $reminders = auth()->user()->reminders;
        $this->showNotification();

        return view('livewire.reminder.show-reminders-mobile', compact('reminders'));
    }
}
