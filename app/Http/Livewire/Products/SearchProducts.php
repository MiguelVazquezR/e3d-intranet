<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\ProductFamily;
use Livewire\Component;

class SearchProducts extends Component
{
    public $query = null,
        $products = [],
        $family,
        $selected_index = -1;

    public function updatedQuery()
    {
        if ($this->family) {
            $this->products = Product::where('name', 'like', "%$this->query%")
                ->Where('product_family_id', $this->family)
                ->take(12)
                ->get()
                ->toArray();
        } else {
            $this->products = Product::where('name', 'like', "%$this->query%")
                ->take(12)
                ->get()
                ->toArray();
        }
    }

    public function clear()
    {
        $this->reset();
    }

    public function incrementIndex()
    {
        if ($this->selected_index == (count($this->products) - 1)) {
            $this->selected_index = 0;
        } else {
            $this->selected_index++;
        }
    }

    public function decrementIndex()
    {
        if ($this->selected_index <= 0) {
            $this->selected_index = count($this->products) - 1;
        } else {
            $this->selected_index--;
        }
    }

    public function selectProduct($index = null)
    {
        if (is_null($index)) {
            $selection = $this->products[$this->selected_index] ?? null;
        } else {
            $selection  = $this->products[$index];
        }

        if ($selection) {
            $this->reset();
            $this->emit('selected-product', $selection);
        }
    }

    public function render()
    {
        return view('livewire.products.search-products', [
            'matching_products' => $this->products,
            'families' => ProductFamily::all(),
        ]);
    }
}
