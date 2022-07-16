<?php

namespace App\Http\Livewire\PayRollMoreTime;

use App\Models\PayRoll;
use App\Models\PayRollMoreTime;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $open = false,
        $report,
        $report_id,
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
            $this->report_id = rand();
        }
    }

    public function mount()
    {
        $this->report_id = rand();
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
        ]);

        $request->report = $this->report->store('files/additional-time-reports', 'public');
        $request->save();

        $this->report_id = rand();

        $this->reset();

        $this->emit('success', 'Solicitud enviada. Esperar respuesta');
    }

    public function render()
    {
        return view('livewire.pay-roll-more-time.create');
    }
}
