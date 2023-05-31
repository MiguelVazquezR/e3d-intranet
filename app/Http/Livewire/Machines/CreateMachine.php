<?php

namespace App\Http\Livewire\Machines;

use App\Models\Machine;
use App\Models\MovementHistory;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMachine extends Component
{
    use WithFileUploads;

    public $open = false,
        $name,
        $serial_number,
        $weight,
        $width,
        $large,
        $height,
        $cost,
        $aquisition_date,
        $days_next_maintenance,
        $files = [];

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
        'days_next_maintenance' => 'required|numeric|min:1',
        'files.*' => 'mimes:jpg,png,jpeg,gif,svg,pdf,docx,doc,txt,xlsx,xlsm,xlsb,xls'
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
        
        // add files attached to machine (manuals, images, features, etc)
        foreach ($this->files as $file) {
            $machine->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('files');
        }

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => auth()->id(),
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
