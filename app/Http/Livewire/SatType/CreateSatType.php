<?php

namespace App\Http\Livewire\SatType;

use App\Models\SatType;
use Livewire\Component;

class CreateSatType extends Component
{
    public $open = false,
        $key,
        $description;

    protected $rules = [
        'key' => 'required|max:10',
        'description' => 'required|max:60',
    ];
    
    protected $listeners = [
        'openModal'
    ];

    public function store()
    {
        $validated = $this->validate();

        SatType::create($validated);

        $this->reset();

        $this->emitTo('customer.create-customer', 'render');
        $this->emit('success', 'Nuevo uso de factura agregado');
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.sat-type.create-sat-type');
    }
}
