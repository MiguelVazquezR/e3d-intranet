<?php

namespace App\Http\Livewire\PayRollMoreTime;

use App\Models\PayRoll;
use App\Models\PayRollMoreTime;
use Livewire\Component;

class Create extends Component
{
    public $open = false,
        $report,
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

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept(['open']);
        }
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function send()
    {
        $this->validate();

        $request = PayRollMoreTime::create([
            'pay_roll_id' => PayRoll::currentPayRoll()->id,
            'user_id' => auth()->user()->id,
            'additional_time' => "$this->hours:$this->minutes",
            'report' => $this->report,
        ]);

        $request->save();

        $this->reset();

        $this->emit('success', 'Solicitud enviada. Esperar respuesta');
    }

    public function render()
    {
        return view('livewire.pay-roll-more-time.create');
    }
}
