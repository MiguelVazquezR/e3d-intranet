<?php

namespace App\Http\Livewire\Machines\SparePart;

use App\Models\Machine;
use Livewire\Component;

class ShowSparePart extends Component
{
    public $open = false,
        $machine;

    protected $listeners = [
        'openModal',
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

    public function render()
    {
        return view('livewire.machines.spare-part.show-spare-part');
    }
}
