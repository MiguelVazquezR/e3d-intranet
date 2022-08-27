<?php

namespace App\Http\Livewire\DesignOrder;

use App\Models\DesignOrder;
use Livewire\Component;

class ShowDesignOrder extends Component
{
    public $design_order,
        $design_results_list = [],
        $open = false;

    protected $listeners = [
        'openModal',
    ];

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    public function mount()
    {
        $this->design_order = new DesignOrder();
    }

    public function openModal(DesignOrder $design_order)
    {
        $this->design_order = $design_order;
        $this->open = true;
        $this->design_results_list = $design_order->results;
    }

    public function render()
    {
        return view('livewire.design-order.show-design-order');
    }
}
