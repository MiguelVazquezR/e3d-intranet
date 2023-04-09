<?php

namespace App\Http\Livewire\Machines;

use App\Models\Machine;
use App\Models\MovementHistory;
use Livewire\Component;

class CreateMachine extends Component
{
    public $open = false,
        $name,
        $serial_number,
        $weight,
        $width,
        $large,
        $height,
        $cost,
        $aquisition_date;

    protected $listeners = [
        'render',
    ];

    protected $rules = [
        'name' => 'required',
        'serial_number' => 'nullable',
        'weight' => 'nullable',
        'width' => 'nullable',
        'large' => 'nullable',
        'height' => 'nullable',
        'cost' => 'nullable',
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

    public function openModal()
    {
        $this->open = true;
    }

    public function store()
    {
       $validated = $this->validate();

        $machine = Machine::create($validated);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' =>auth()->id(),
            'description' => "Se agregó nueva máquina de nombre: {$machine->name}"
        ]);

        $this->reset();

        $this->emitTo('machines.machine-index', 'render');
        $this->emit('success', 'Nueva máquina registrada');
    }

    public function render()
    {
        return view('livewire.machines.create-machine');
    }
}
