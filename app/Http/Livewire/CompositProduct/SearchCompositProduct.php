<?php

namespace App\Http\Livewire\CompositProduct;

use App\Models\CompositProduct;
use Livewire\Component;

class SearchCompositProduct extends Component
{
    public $query = null,
        $composit_products = [],
        $company_id,
        $selected_index = -1;

    protected $listeners = [
        'update-company' => 'updateCompany',
    ];

    public function updatedQuery()
    {
        $this->composit_products = CompositProduct::where('alias', 'like', "%$this->query%")
            ->where('company_id', $this->company_id)
            ->take(10)
            ->get()
            ->toArray();
    }

    public function clear()
    {
        $this->resetExcept('company_id');
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

    public function updateCompany($company_id)
    {
        $this->company_id = $company_id;
    }

    public function render()
    {
        return view('livewire.composit-product.search-composit-product', [
            'matching_composit_products' => $this->composit_products,
        ]);
    }
}
