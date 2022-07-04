<?php

namespace App\Http\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Component;

class ShowSupplier extends Component
{
    public $supplier,
        $open = false;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->supplier = new Supplier();
    }

    public function openModal(supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.supplier.show-supplier');
    }
}
