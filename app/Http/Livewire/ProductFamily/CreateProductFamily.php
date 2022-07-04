<?php

namespace App\Http\Livewire\ProductFamily;

use App\Models\ProductFamily;
use Livewire\Component;

class CreateProductFamily extends Component
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
        return view('livewire.product-family.create-product-family');
    }

    public function store()
    {
        $this->validate();

        ProductFamily::create([
            'name'  =>  $this->name
        ]);

        $this->reset();

        $this->emitTo('products.create-product','render');
        $this->emit('success', 'Nueva familia agregada');
    }

    public function openModal()
    {
        $this->open = true;
    }
}
