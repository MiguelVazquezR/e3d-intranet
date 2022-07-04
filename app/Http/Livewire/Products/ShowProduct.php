<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class ShowProduct extends Component
{
    public $open = false,
        $product;

    protected $listeners = [
        'openModal',
    ];

    public $image_extensions = [
        'png',
        'jpg',
        'jpeg',
        'bmp',
    ];

    public function mount()
    {
        $this->product = new Product();
    }

    public function openModal(Product $product)
    {
        $this->product = $product;
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.products.show-product');
    }
}
