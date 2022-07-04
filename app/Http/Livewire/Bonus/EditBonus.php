<?php

namespace App\Http\Livewire\Bonus;

use App\Models\Bonus;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditBonus extends Component
{
    public $open = false,
        $name,
        $full_time,
        $half_time;

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'bonus.name' => 'required',
        'bonus.full_time' => 'required|numeric',
        'bonus.half_time' => 'required|numeric',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'bonus',
            ]);
        }
    }

    public function openModal(Bonus $bonus)
    {
        $this->open = true;
        $this->bonus = $bonus;
    }

    public function update()
    {
        $this->validate();

        $this->bonus->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó bono con ID: {$this->bonus->id}"
        ]);

        $this->resetExcept('bonus');

        $this->emitTo('bonus.bonuses', 'render');
        $this->emit('success', 'Bono actualizado');
    }

    public function render()
    {
        return view('livewire.bonus.edit-bonus');
    }
}
