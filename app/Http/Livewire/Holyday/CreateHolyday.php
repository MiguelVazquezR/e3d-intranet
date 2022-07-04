<?php

namespace App\Http\Livewire\Holyday;

use App\Models\Holyday;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateHolyday extends Component
{
    public $open = false,
        $name,
        $day,
        $month,
        $active,
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
    ];

    protected $rules = [
        'name' => 'required',
        'day' => 'required',
        'month' => 'required',
        'active' => 'required',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function store()
    {
        $this->validate();

        $holyday = Holyday::create([
            'name' => $this->name,
            'date' => '2022-'.$this->month.'-'.$this->day,
            'active' => $this->active,
        ]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó nuevo día feriado de nombre: {$holyday->name}"
        ]);

        $this->reset();

        $this->emitTo('holyday.holydays', 'render');
        $this->emit('success', 'Nuevo día festivo registrado');
    }

    public function render()
    {
        return view('livewire.holyday.create-holyday');
    }
}
