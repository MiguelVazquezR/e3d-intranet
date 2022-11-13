<?php

namespace App\Http\Livewire\MarketingResults;

use App\Models\MarketingOrder;
use App\Models\MarketingOrderResult;
use App\Notifications\FinishedOrderNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMarketingResults extends Component
{
    use WithFileUploads;

    public $open = false,
        $external_link,
        $media_id,
        $marketing_order,
        $notes,
        $emit_response_to;

    protected $rules = [
        'image' => 'required',
        'notes' => 'max:300',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal(MarketingOrder $marketing_order, $emit_response_to)
    {
        $this->open = true;
        $this->emit_response_to = $emit_response_to;
        $this->marketing_order = $marketing_order;
    }

    public function store()
    {
        $this->validate();

        // MarketingOrderResult::create([
        //     'image' => $image_url,
        //     'notes' => $this->notes,
        //     'marketing_order_id' => $this->marketing_order->id,
        // ]);

        $this->marketing_order->update([
            'status' => 'Terminado',
        ]);

        // notify to request's creator
        $this->marketing_order->creator->notify(new FinishedOrderNotification('mercadotecnia', $this->marketing_order->id, 'Se agregó un nuevo resultado', 'marketing-orders'));

        $this->reset();

        $this->emit('success', 'Resultado subido');
        $this->emitTo($this->emit_response_to, 'load-marketing-result');
    }

    public function render()
    {
        return view('livewire.marketing-results.create-marketing-results');
    }
}
