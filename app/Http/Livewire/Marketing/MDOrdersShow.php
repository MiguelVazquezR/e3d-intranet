<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MarketingOrder;
use App\Notifications\StartedOrderNotification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class MDOrdersShow extends Component
{
    public $marketing_order,
        $open = false,
        $tentative_end,
        $marketing_results_list = [];

    protected $rules = [
        'tentative_end' => 'required|date|after:today',
    ];

    protected $listeners = [
        'openModal',
        'load-marketing-result' => 'loadMarketingResults',
    ];

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
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

    public function loadMarketingResults()
    {
        $this->marketing_results_list = $this->marketing_order->results;
    }

    public function addMarketingResult()
    {
        $this->emitTo(
            'marketing-results.create-marketing-results',
            'openModal',
            $this->marketing_order,
            'marketing.m-d-orders-show'
        );
    }

    public function deleteItem($index)
    {
        Storage::delete([$this->marketing_results_list[$index]->image]);
        $this->marketing_results_list[$index]->delete();
        unset($this->marketing_results_list[$index]);
    }

    public function storeTentativeEnd()
    {
        $this->validate();

        $this->marketing_order->update([
            'tentative_end' => $this->tentative_end,
            'started_at' => now(),
            'status' => 'En proceso',
        ]);

        // notify to request's creator
        $this->marketing_order->creator->notify(new StartedOrderNotification('orden de mercadotecnia', $this->marketing_order->id, 'marketing-orders'));

        $this->resetExcept('marketing_order');

        $this->emitTo('marketing.m-d-orders-index', 'render');
        $this->emit('success', 'Fecha tentativa de entrega establecida');
    }

    public function render()
    {
        return view('livewire.marketing.m-d-orders-show');
    }
}
