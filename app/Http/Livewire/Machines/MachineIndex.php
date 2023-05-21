<?php

namespace App\Http\Livewire\Machines;

use App\Models\Machine;
use App\Models\MovementHistory;
use Livewire\Component;
use Livewire\WithPagination;

class MachineIndex extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'name' => 'nombre',
        'serial_number' => 'número de serie',
    ];

    protected $listeners = [
        'render',
        'delete',
        'edit',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingElements()
    {
        $this->resetPage();
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function show(Machine $machine)
    {
        $this->emitTo('machines.show-machine', 'openModal', $machine);
    }

    public function edit(Machine $machine)
    {
        $this->emitTo('machines.edit-machine', 'openModal', $machine);
    }

    public function delete(Machine $machine)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => auth()->id(),
            'description' => "Se eliminó máquina de nombre: {$machine->name}"
        ]);

        $machine->delete();

        $this->emit('success', 'maquina eliminada');
    }

    public function openMaintenances(Machine $machine)
    {
        $this->emitTo('machines.maintenance.index-maintenance', 'openModal', $machine);
    }
    
    public function openSpareParts(Machine $machine)
    {
        $this->emitTo('machines.spare-part.index-spare-part', 'openModal', $machine);
    }

    public function render()
    {
        $machines = Machine::where('id', 'like', "%$this->search%")
            ->orWhere('name', 'like', "%$this->search%")
            ->orWhere('serial_number', 'like', "%$this->search%")
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        return view('livewire.machines.machine-index', compact('machines'));
    }
}
