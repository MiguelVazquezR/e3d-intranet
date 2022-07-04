<?php

namespace App\Http\Livewire\Holyday;

use App\Models\Holyday;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditHolyday extends Component
{
    public $open = false,
        $holyday,
        $day,
        $month,
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'holyday.name' => 'required',
        'holyday.active' => 'required',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal(Holyday $holyday)
    {
        $this->open = true;
        $this->holyday = $holyday;
        $this->day = $this->holyday->date->isoFormat('D');
        $this->month = $this->holyday->date->isoFormat('M');
    }

    public function update()
    {
        $this->validate();

        $this->holyday->date = '2022-'.$this->month.'-'.$this->day;

        $this->holyday->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó día feriado con ID: {$this->holyday->id}"
        ]);

        $this->reset();

        $this->emitTo('holyday.holydays', 'render');
        $this->emit('success', 'Día festivo actualizado');
    }

    public function render()
    {
        return view('livewire.holyday.edit-holyday');
    }
}
