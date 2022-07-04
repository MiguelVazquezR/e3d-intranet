<?php

namespace App\Http\Livewire\CompanyHasProductForSell;

use App\Models\CompanyHasProductForSell;
use Livewire\Component;

class SearchProductsForSell extends Component
{
    // public $query = null,
    //     $products_for_sell = [],
    //     $company_id,
    //     $selected_index = -1;

    // protected $listeners = [
    //     'update-company' => 'updateCompany',
    // ];

    // public function updatedQuery()
    // {
    //     $this->products_for_sell = CompanyHasProductForSell::whereHas('model', function ($query) {
    //         if($query)
    //         $query->where('model', 'like', "%$this->query%")
    //     })
    //         ->where('company_id', $this->company_id)
    //         ->take(10)
    //         ->get()
    //         ->toArray();
    // }

    // public function clear()
    // {
    //     $this->resetExcept('company_id');
    // }

    // public function incrementIndex()
    // {
    //     if ($this->selected_index == (count($this->products_for_sell) - 1)) {
    //         $this->selected_index = 0;
    //     } else {
    //         $this->selected_index++;
    //     }
    // }

    // public function decrementIndex()
    // {
    //     if ($this->selected_index <= 0) {
    //         $this->selected_index = count($this->products_for_sell) - 1;
    //     } else {
    //         $this->selected_index--;
    //     }
    // }

    // public function selectCompositProduct($index = null)
    // {
    //     if (is_null($index)) {
    //         $selection = $this->products_for_sell[$this->selected_index] ?? null;
    //     } else {
    //         $selection  = $this->products_for_sell[$index];
    //     }

    //     $this->resetExcept('company_id');

    //     $this->emit('selected-composit-product', $selection);
    // }

    // public function updateCompany($company_id)
    // {
    //     $this->company_id = $company_id;
    // }

    // public function render()
    // {
    //     return view('livewire.company-has-product-for-sell.search-products-for-sell', [
    //         'matching_products_for_sell' => $this->products_for_sell,
    //     ]);
    // }
}
