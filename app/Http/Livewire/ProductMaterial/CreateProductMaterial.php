<?php

namespace App\Http\Livewire\ProductMaterial;

use App\Models\ProductFamily;
use App\Models\ProductMaterial;
use Livewire\Component;

class CreateProductMaterial extends Component
{
    public $open = false;

    public $name,
    $product_family_id;

    protected $rules = [
        'name' => 'required|max:191',
        'product_family_id' => 'required',
    ];
    protected $listeners = [
        'openModal'
    ];

    public function render()
    {
        return view('livewire.product-material.create-product-material', [
            'families' => ProductFamily::all(),
        ]);
    }

    public function store()
    {
        $data = $this->validate();

        ProductMaterial::create($data);

        $this->reset();

        $this->emitTo('products.create-product','updatedProductFamilyId');
        $this->emit('success', 'Nuevo material agregado');
    }

    public function openModal()
    {
        $this->open = true;
    }
}
