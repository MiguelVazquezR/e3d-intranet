<?php

namespace App\View\Components;

use App\Models\CompositProduct;
use Illuminate\View\Component;

class CompositProductCard extends Component
{
    public $composit_product,
    $vertical;

    public function __construct(CompositProduct $compositProduct, $vertical = true)
    {
        $this->composit_product = $compositProduct;
        $this->vertical = $vertical;
    }

    
    public function render()
    {
        return view('components.composit-product-card');
    }
}
