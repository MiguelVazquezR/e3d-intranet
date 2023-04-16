<?php

namespace App\Http\Livewire\Machines;

use App\Models\Machine;
use App\Models\SparePart;
use Livewire\Component;

class SpareParts extends Component
{
    public $open = false,
        $machine;
       
    protected $listeners = [
        'render',
        'openModal',
    ];

    // protected $rules = [
    //     'machine.name' => 'required',
    //     'machine.serial_number' => 'required',
    //     'machine.weight' => 'nullable',
    //     'machine.width' => 'nullable',
    //     'machine.large' => 'nullable',
    //     'machine.height' => 'nullable',
    //     'machine.cost' => 'nullable',
    //     'aquisition_date' => 'nullable',
    // ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'machine',
            ]);
        }
    }

    public function openModal(Machine $machine)
    {
        $this->open = true;
        $this->machine = $machine;
    }

    public function mount()
    {
        $this->machine = new Machine();
    }


    public function render()
    {
        return view('livewire.machines.spare-parts');
    }
}
