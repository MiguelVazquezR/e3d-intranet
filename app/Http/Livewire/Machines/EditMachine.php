<?php

namespace App\Http\Livewire\Machines;

use App\Models\Machine;
use App\Models\MovementHistory;
use Livewire\Component;

class EditMachine extends Component
{
    public $open = false,
        $aquisition_date,
        $machine;
       
    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'machine.name' => 'required',
        'machine.serial_number' => 'required',
        'machine.weight' => 'nullable',
        'machine.width' => 'nullable',
        'machine.large' => 'nullable',
        'machine.height' => 'nullable',
        'machine.cost' => 'nullable',
        'aquisition_date' => 'nullable',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal(Machine $machine)
    {
        $this->open = true;
        $this->machine = $machine;
        $this->aquisition_date = $machine->aquisition_date->toDateString();
    }

    public function update()
    {
        $this->validate();

        $this->machine->aquisition_date = $this->aquisition_date;
        $this->machine->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => auth()->id(),
            'description' => "Se editó maquina con ID: {$this->machine->id}"
        ]);

        $this->reset();

        $this->emitTo('machines.machine-index', 'render');
        $this->emit('success', 'máquina actualizada');
    }

    public function render()
    {
        return view('livewire.machines.edit-machine');
    }
}
