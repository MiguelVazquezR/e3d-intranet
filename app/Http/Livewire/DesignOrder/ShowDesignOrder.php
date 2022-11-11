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
        'createModificationRequest',
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

    public function seeOrder($order_id)
    {
        $this->design_order = DesignOrder::findOrFail($order_id);
    }

    public function requestModifications()
    {
        $this->emitTo('design-order.request-modifications', 'openModal');
    }
    
    // method called by RequestModification by event
    public function createModificationRequest($especifications)
    {
        $clone = $this->clone($especifications);
        $this->design_order->modified_id = $clone->id;
        $this->design_order->save();
        $this->emit('design-order.design-orders', 'render');
        $this->emit('success', 'Se ha mandado la solicitud de modificaciones al diseñador');
    }

    public function clone($especifications)
    {
        $clone = $this->design_order->replicate(['especifications', 'original_id', 'status']);
        $clone->especifications = $especifications;
        $clone->original_id = $this->design_order->id;
        $clone->status = 'Autorizado. Sin iniciar';
        $clone->save();

        return $clone;
    }

    public function render()
    {
        return view('livewire.design-order.show-design-order');
    }
}
