<?php

namespace App\Http\Livewire\Bonus;

use App\Models\Bonus;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateBonus extends Component
{
    public $open = false,
        $name,
        $full_time,
        $half_time;

    protected $listeners = [
        'render',
    ];

    protected $rules = [
        'name' => 'required',
        'full_time' => 'required|numeric',
        'half_time' => 'required|numeric',
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
        $validation = $this->validate();

        $bonus = Bonus::create($validation);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó nuevo bono de nombre: {$bonus->name}"
        ]);

        $this->reset();

        $this->emitTo('bonus.bonuses', 'render');
        $this->emit('success', 'Nuevo bono registrado');
    }


    public function render()
    {
        return view('livewire.bonus.create-bonus');
    }
}
