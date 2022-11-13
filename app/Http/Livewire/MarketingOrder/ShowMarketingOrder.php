<?php

namespace App\Http\Livewire\MarketingOrder;

use App\Models\MarketingOrder;
use Livewire\Component;

class ShowMarketingOrder extends Component
{
    public $marketing_order,
        $marketing_results_list = [],
        $open = false;

    protected $listeners = [
        'openModal',
        'createModificationRequest',
    ];

    public function mount()
    {
        $this->marketing_order = new MarketingOrder();
    }

    public function openModal(MarketingOrder $marketing_order)
    {
        $this->marketing_order = $marketing_order;
        $this->open = true;
        $this->marketing_results_list = $marketing_order->results;
    }

    public function seeOrder($order_id)
    {
        $this->marketing_order = MarketingOrder::findOrFail($order_id);
    }

    public function requestModifications()
    {
        $this->emitTo('marketing-order.request-modifications', 'openModal');
    }
    
    // method called by RequestModification by event
    public function createModificationRequest($especifications)
    {
        $clone = $this->clone($especifications);
        $this->marketing_order->modified_id = $clone->id;
        $this->marketing_order->save();
        $this->emit('marketing-order.marketing-orders', 'render');
        $this->emit('success', 'Se ha mandado la solicitud de modificaciones al departamento');
    }

    public function clone($especifications)
    {
        $clone = $this->marketing_order->replicate(['especifications', 'original_id', 'status']);
        $clone->especifications = $especifications;
        $clone->original_id = $this->marketing_order->id;
        $clone->status = 'Autorizado. Sin iniciar';
        $clone->save();

        return $clone;
    }

    public function render()
    {
        return view('livewire.marketing-order.show-marketing-order');
    }
}
