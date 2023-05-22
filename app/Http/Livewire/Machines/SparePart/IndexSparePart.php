<?php

namespace App\Http\Livewire\Machines\SparePart;

use App\Models\Machine;
use App\Models\SparePart;
use Livewire\Component;

class IndexSparePart extends Component
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

    public function openCreateSparePartModal()
    {
        $this->emitTo('machines.spare-part.create-spare-part', 'openModal', $this->machine->id);
    }

    public function openEditModal(SparePart $spare_part)
    {
        $this->emitTo('machines.spare-part.edit-spare-part', 'openModal', $spare_part);
    }

    public function render()
    {
        return view('livewire.machines.spare-part.index-spare-part');
    }
}
