<?php

namespace App\Http\Livewire\MarketingOrder;

use Livewire\Component;

class RequestModifications extends Component
{
    public $open = false,
        $modifications;

    protected $listeners = [
        'openModal',
    ];

    public function openModal()
    {
        $this->open = true;
    }

    public function sendRequest()
    {
        $this->emitTo('marketing-order.show-marketing-order', 'createModificationRequest', $this->modifications);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.marketing-order.request-modifications');
    }
}
