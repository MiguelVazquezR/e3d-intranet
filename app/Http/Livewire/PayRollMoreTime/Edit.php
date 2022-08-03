<?php

namespace App\Http\Livewire\PayRollMoreTime;

use App\Models\PayRollMoreTime;
use Livewire\Component;

class Edit extends Component
{
    public $open = false,
        $time_request,
        $report,
        $_report,
        $minutes,
        $hours;

    protected $rules = [
        'hours' => 'required|numeric|min:0|max:15',
        'minutes' => 'required|numeric|min:0|max:59',
        'report' => 'required',
    ];

    protected $listeners = [
        'openModal',
        'render',
    ];

    public function mount()
    {
        $this->time_request = new PayRollMoreTime;
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept(['open, time_request']);
        }
    }

    public function openModal(PayRollMoreTime $time_request)
    {
        $this->open = true;
        $this->time_request = $time_request;
        $this->hours = explode(':', $this->time_request->additional_time)[0];
        $this->_report = $this->time_request->report;
        $this->minutes = explode(':', $this->time_request->additional_time)[1];
    }

    public function update()
    {
        $this->validate();

        $this->time_request->update([
            'additional_time' => "$this->hours:$this->minutes",
            'report' => $this->report,
            'authorized_by' => null,
            'authorized_at' => null,
        ]);

        $this->resetExcept('time_request');

        $this->emit('success', 'Solicitud editada. Esperar respuesta');
    }

    public function render()
    {
        return view('livewire.pay-roll-more-time.edit');
    }
}
