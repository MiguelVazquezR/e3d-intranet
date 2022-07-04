<?php

namespace App\Http\Livewire\MeasurementUnit;

use App\Models\MeasurementUnit;
use Livewire\Component;

class CreateMeasurementUnit extends Component
{
    public $open = false;
    public $name;
    protected $rules = [
        'name' => 'required|max:191',
    ];
    protected $listeners = [
        'openModal'
    ];

    public function render()
    {
        return view('livewire.measurement-unit.create-measurement-unit');
    }

    public function store()
    {
        $this->validate();

        MeasurementUnit::create([
            'name'  =>  $this->name
        ]);

        $this->reset();

        $this->emitTo('products.create-product','render');
        $this->emit('success', 'Nueva unidad agregada');
    }

    public function openModal()
    {
        $this->open = true;
    }
}
