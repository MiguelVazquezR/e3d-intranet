<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\PurchaseOrder;
use Livewire\Component;

class ShowPurchaseOrder extends Component
{
    public $purchase_order,
        $active_tab = 0,
        $open = false;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->purchase_order = new PurchaseOrder();
        $this->purchase_order->user_id = 1;
    }

    public function openModal(PurchaseOrder $purchase_order)
    {
        $this->purchase_order = $purchase_order;
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.purchase-order.show-purchase-order');
    }
}
