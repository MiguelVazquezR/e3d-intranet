<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\View\Component;

class SimpleProductCard extends Component
{
    public $simple_product,
    $vertical;

    public function __construct(Product $simpleProduct, $vertical = true)
    {
        $this->simple_product = $simpleProduct;
        $this->vertical = $vertical;
    }

    public function render()
    {
        return view('components.simple-product-card');
    }
}
