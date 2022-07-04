<?php

namespace App\Http\Livewire\CompositProduct;

use App\Models\CompositProduct;
use Livewire\Component;

class SearchAllCompositProduct extends Component
{
    public $query = null,
        $composit_products = [],
        $selected_index = -1;

    public function updatedQuery()
    {
        $this->composit_products = CompositProduct::where('alias', 'like', "%$this->query%")
            ->take(10)
            ->get()
            ->toArray();
    }

    public function clear()
    {
        $this->reset();
    }

    public function incrementIndex()
    {
        if ($this->selected_index == (count($this->composit_products) - 1)) {
            $this->selected_index = 0;
        } else {
            $this->selected_index++;
        }
    }

    public function decrementIndex()
    {
        if ($this->selected_index <= 0) {
            $this->selected_index = count($this->composit_products) - 1;
        } else {
            $this->selected_index--;
        }
    }

    public function selectCompositProduct($index = null)
    {
        if (is_null($index)) {
            $selection = $this->composit_products[$this->selected_index] ?? null;
        } else {
            $selection  = $this->composit_products[$index];
        }

        if ($selection) {
            $this->resetExcept('company_id');
            $this->emit('selected-composit-product', $selection);
        }
    }

    public function render()
    {
        return view('livewire.composit-product.search-all-composit-product', [
            'matching_composit_products' => $this->composit_products,
        ]);
    }
}
