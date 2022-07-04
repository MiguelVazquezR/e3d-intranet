<?php

namespace App\Http\Livewire\SellOrder;

use App\Models\SellOrder;
use Livewire\Component;

class ShowSellOrder extends Component
{
    public $sell_order,
        $open = false,
        $active_tab = 0;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->sell_order = new SellOrder();
    }

    public function openModal(SellOrder $sell_order)
    {
        $this->sell_order = $sell_order;
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.sell-order.show-sell-order');
    }
}
