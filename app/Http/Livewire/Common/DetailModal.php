<?php

namespace App\Http\Livewire\Common;

use App\Models\MovementHistory;
use Livewire\Component;

class DetailModal extends Component
{
    public $open = false,
        $movement_type = 1,
        $movements = [];

    protected $listeners = [
        'openModal',
        'deleteAll',
    ];

    public function openModal($movement_type)
    {
        $this->movements = MovementHistory::where('movement_type', $movement_type)->latest()->get();
        $this->movement_type = $movement_type;
        $this->open = true;
    }
    
    public function deleteAll()
    {
        MovementHistory::where('movement_type', $this->movement_type)->delete();

        $this->emit('success', 'Se han eliminado todos los registros');
        $this->reset("movements");
    }

    public function render()
    {
        return view('livewire.common.detail-modal');
    }
}
