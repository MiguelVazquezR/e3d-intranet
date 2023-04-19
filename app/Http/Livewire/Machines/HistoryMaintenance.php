<?php

namespace App\Http\Livewire\Machines;

use App\Models\Machine;
use App\Models\MovementHistory;
use Livewire\Component;

class HistoryMaintenance extends Component
{
    public $open = false,
        $aquisition_date,
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
            ]);
        }
    }

    public function openModal(Machine $machine)
    {
        $this->open = true;
        $this->machine = $machine;
        $this->aquisition_date = $machine->aquisition_date->toDateString();
    }


    public function render()
    {
        return view('livewire.machines.history-maintenance');
    }
}
