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
        $this->convertions = array_fill(0, $purchase_order->purchaseOrderedProducts->count(), 1);
        $this->open = true;
    }

    public function receive()
    {
        $this->emitTo('purchase-order.purchase-orders', 'receiveOrder', $this->purchase_order, $this->convertions);
        $this->resetExcept('purchase_order');
    }

    public function render()
    {
        return view('livewire.purchase-order.units-convertion');
    }
}
