<?php

namespace App\Http\Livewire\Machines\Maintenance;

use App\Models\Maintenance;
use App\Models\MovementHistory;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMaintenance extends Component
{
    use WithFileUploads;

    public $open = false,
        $machine_id,
        $problems,
        $actions,
        $responsible,
        $cost,
        $maintenance_type;

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'problems' => 'required',
        'actions' => 'required',
        'responsible' => 'required',
        'cost' => 'required|numeric',
        'maintenance_type' => 'required',
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

        $maintenance = Maintenance::create($validated + ['machine_id' => $this->machine_id]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => auth()->id(),
            'description' => "Se agregó nuevo mantenimiento con la siguiente situción: {$maintenance->problems}"
        ]);

        $this->reset();

        $this->emitTo('machines.maintenance.index-maintenance', 'render');
        $this->emit('success', 'Nuevo mantenimiento registrado');
    }

    public function render()
    {
        return view('livewire.machines.maintenance.create-maintenance');
    }
}
