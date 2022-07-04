<?php

namespace App\Http\Livewire\SatMethod;

use App\Models\SatMethod;
use Livewire\Component;

class CreateSatMethod extends Component
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

        SatMethod::create($validated);

        $this->reset();

        $this->emitTo('customer.create-customer', 'render');
        $this->emit('success', 'Nuevo método agregado');
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.sat-method.create-sat-method');
    }
}
