<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use Livewire\Component;

class SearchCustomer extends Component
{
    public $query = null,
        $customers = [],
        $selected_index = -1;

    public function updatedQuery()
    {
        $this->customers = Customer::where('name', 'like', "%$this->query%")
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
        if ($this->selected_index == (count($this->customers) - 1)) {
            $this->selected_index = 0;
        } else {
            $this->selected_index++;
        }
    }

    public function decrementIndex()
    {
        if ($this->selected_index <= 0) {
            $this->selected_index = count($this->customers) - 1;
        } else {
            $this->selected_index--;
        }
    }

    public function selectCustomer($index = null)
    {
        if (is_null($index)) {
            $selection = $this->customers[$this->selected_index] ?? null;
        } else {
            $selection  = $this->customers[$index];
        }

        if ($selection) {
            $this->reset();
            $this->emit('selected-customer', $selection);
        }
    }

    public function render()
    {
        return view('livewire.customer.search-customer', [
            'matching_customers' => $this->customers,
        ]);
    }
}
