<?php

namespace App\Http\Livewire\ProductStatus;

use App\Models\ProductStatus;
use Livewire\Component;

class CreateProductStatus extends Component
{
    public $open = false;
    public $name;
    protected $rules = [
        'name' => 'required|max:191',
    ];
    protected $listeners = [
        'openModal'
    ];

    public function store()
    {
        $this->validate();

        ProductStatus::create([
            'name'  =>  $this->name
        ]);

        $this->reset();

        $this->emitTo('products.create-product','render');
        $this->emitTo('products.edit-product','render');
        $this->emit('success', 'Nuevo material agregado');
    }

    public function openModal()
    {
        $this->open = true;
    }
    public function render()
    {
        return view('livewire.product-status.create-product-status');
    }
}
