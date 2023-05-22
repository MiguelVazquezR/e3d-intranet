<?php

namespace App\Http\Livewire\Machines\Maintenance;

use App\Models\Machine;
use App\Models\Maintenance;
use Livewire\Component;

class IndexMaintenance extends Component
{
    public $open = false,
        $machine;

    protected $listeners = [
        'openModal',
        'updateModel',
    ];

    // public $image_extensions = [
    //     'png',
    //     'jpg',
    //     'jpeg',
    //     'bmp',
    // ];

    public function mount()
    {
        $this->machine = new Machine();
    }

    public function openModal(Machine $machine)
    {
        $this->machine = $machine;
        $this->open = true;
    }

    public function updateModel()
    {
        $this->machine = Machine::find($this->machine->id);
        $this->render();
    }

    public function openCreateMaintenanceModal()
    {
        $this->emitTo('machines.maintenance.create-maintenance', 'openModal', $this->machine->id);
    }

    public function openEditModal(Maintenance $maintenance)
    {
        $this->emitTo('machines.maintenance.edit-maintenance', 'openModal', $maintenance);
    }

    public function render()
    {
        return view('livewire.machines.maintenance.index-maintenance');
    }
}
