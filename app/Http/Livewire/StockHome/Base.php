<?php

namespace App\Http\Livewire\StockHome;

use Livewire\Component;

class Base extends Component
{
    public $simple_product_stock = true;

    public function render()
    {
        return view('livewire.stock-home.base');
    }
}
