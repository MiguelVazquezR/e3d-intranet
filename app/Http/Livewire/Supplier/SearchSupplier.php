<?php

namespace App\Http\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Component;

class SearchSupplier extends Component
{
    public $query = null,
        $suppliers = [],
        $selected_index = -1;

    public function updatedQuery()
    {
        $this->suppliers = Supplier::where('name', 'like', "%$this->query%")
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
        if ($this->selected_index == (count($this->suppliers) - 1)) {
            $this->selected_index = 0;
        } else {
            $this->selected_index++;
        }
    }

    public function decrementIndex()
    {
        if ($this->selected_index <= 0) {
            $this->selected_index = count($this->suppliers) - 1;
        } else {
            $this->selected_index--;
        }
    }

    public function selectsupplier($index = null)
    {
        if (is_null($index)) {
            $selection = $this->suppliers[$this->selected_index] ?? null;
        } else {
            $selection  = $this->suppliers[$index];
        }

        if ($selection) {
            $this->reset();
            $this->emit('selected-supplier', $selection);
        }
    }

    public function render()
    {
        return view('livewire.supplier.search-supplier', [
            'matching_suppliers' => $this->suppliers,
        ]);
    }

}
