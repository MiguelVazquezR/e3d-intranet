<?php

namespace App\Http\Livewire\CompositProduct;

use App\Models\CompositProduct;
use Livewire\Component;

class ShowCompositProduct extends Component
{
    public $open = false,
        $composit_product;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->composit_product = new CompositProduct();
    }

    public function openModal(CompositProduct $composit_product)
    {
        $this->composit_product = $composit_product;
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.composit-product.show-composit-product');
    }
}
