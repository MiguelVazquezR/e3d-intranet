<?php

namespace App\Http\Livewire\Machines;

use Livewire\Component;

class EditMachine extends Component
{
    public $open = false,
        $machine;
       
    protected $listeners = [
        'render',
        'openModal',
    ];

    // protected $rules = [
    //     'holyday.name' => 'required',
    //     'holyday.active' => 'required',
    // ];

    // public function updatingOpen()
    // {
    //     if ($this->open == true) {
    //         $this->resetExcept([
    //             'open',
    //         ]);
    //     }
    // }

    // public function openModal(Holyday $holyday)
    // {
    //     $this->open = true;
    //     $this->holyday = $holyday;
    //     $this->day = $this->holyday->date->isoFormat('D');
    //     $this->month = $this->holyday->date->isoFormat('M');
    // }

    // public function update()
    // {
    //     $this->validate();

    //     $this->holyday->date = '2022-' . $this->month . '-' . $this->day;

    //     $this->holyday->save();

    //     // create movement history
    //     MovementHistory::create([
    //         'movement_type' => 2,
    //         'user_id' => Auth::user()->id,
    //         'description' => "Se editó día feriado con ID: {$this->holyday->id}"
    //     ]);

    //     $this->reset();

    //     $this->emitTo('holyday.holydays', 'render');
    //     $this->emit('success', 'Día festivo actualizado');
    // }

    public function render()
    {
        return view('livewire.machines.edit-machine');
    }
}
