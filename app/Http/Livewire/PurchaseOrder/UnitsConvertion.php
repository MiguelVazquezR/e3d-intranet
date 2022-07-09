<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\PurchaseOrder;
use Livewire\Component;

class UnitsConvertion extends Component
{
    public $open = false,
        $purchase_order,
        $convertions = [];

    protected $listeners = [
        'openModal'
    ];

    public function mount()
    {
        $this->purchase_order = new PurchaseOrder;
    }

    public function openModal(PurchaseOrder $purchase_order)
    {
        $this->purchase_order = $purchase_order;
        $this->all_products = $purchase_order->purchaseOrderedProducts;
        array_fill(0, $purchase_order->purchaseOrderedProducts->count(), 1);
        $this->open = true;
    }

    public function receive()
    {
        dd($this->convertions);
    }

    public function render()
    {
        return view('livewire.purchase-order.units-convertion');
    }
}
