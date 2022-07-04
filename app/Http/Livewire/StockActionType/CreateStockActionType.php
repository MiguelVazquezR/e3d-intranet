<?php

namespace App\Http\Livewire\StockActionType;

use App\Models\StockActionType;
use Livewire\Component;

class CreateStockActionType extends Component
{
    public $open = false;
    public $name,
           $movement = 1;
    protected $rules = [
        'name' => 'required|max:191',
        'movement' => 'required',
    ];
    protected $listeners = [
        'openModal'
    ];

    public function store()
    {
        $this->validate();

        StockActionType::create([
            'name'  =>  $this->name,
            'movement'  =>  $this->movement
        ]);

        $this->reset();

        $this->emit('success', 'Nuevo tipo de moviemiento agregado');
        $this->emitTo('stock-movement.create-stock-movement','render');
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.stock-action-type.create-stock-action-type');
    }
}
