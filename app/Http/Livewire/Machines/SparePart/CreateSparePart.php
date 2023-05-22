<?php

namespace App\Http\Livewire\Machines\SparePart;

use App\Models\MovementHistory;
use App\Models\SparePart;
use Livewire\Component;

class CreateSparePart extends Component
{
    public $open = false,
        $machine_id,
        $name,
        $quantity,
        $supplier,
        $description,
        $cost,
        $location;

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'name' => 'required',
        'quantity' => 'required|numeric|min:1',
        'supplier' => 'required',
        'description' => 'required',
        'cost' => 'required|numeric',
        'location' => 'required',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal($machine_id)
    {
        $this->open = true;
        $this->machine_id = $machine_id;
    }

    public function store()
    {
        $validated = $this->validate();

        $spare_part = SparePart::create($validated + ['machine_id' => $this->machine_id]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => auth()->id(),
            'description' => "Se agregó nueva refacción con el siguiente nombre: {$spare_part->name}"
        ]);

        $this->reset();

        $this->emitTo('machines.spare-part.index-spare-part', 'updateModel');
        $this->emit('success', 'Nueva refacción registrado');
    }

    public function render()
    {
        return view('livewire.machines.spare-part.create-spare-part');
    }
}
