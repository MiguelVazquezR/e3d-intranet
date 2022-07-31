<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use Livewire\Component;

class CreateReminder extends Component
{
    public $open = false,
        $title,
        $remind_at;

    protected $rules = [
        'title' => 'required|max:10',
    ];

    protected $listeners = [
        'openModal'
    ];

    public function openModal()
    {
        $this->open = true;
    }

    public function store()
    {
        $this->rules['remind_at'] = "required|date|after_or_equal:".now()->isoFormat('YYYY-MM-DD');
        $data = $this->validate();
        Reminder::create($data + ['user_id' => auth()->user()->id]);

        $this->emit('success', 'Recordatorio agregado');
        $this->emitTo('reminder.drop-down', 'render');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.reminder.create-reminder');
    }
}
