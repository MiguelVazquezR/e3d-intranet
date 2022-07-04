<?php

namespace App\Http\Livewire\SatWay;

use App\Models\SatWay;
use Livewire\Component;

class CreateSatWay extends Component
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

        SatWay::create($validated);

        $this->reset();

        $this->emitTo('customer.create-customer', 'render');
        $this->emit('success', 'Nueva forma de pago agregada');
    }

    public function openModal()
    {
        $this->open = true;
    }


    public function render()
    {
        return view('livewire.sat-way.create-sat-way');
    }
}
