<?php

namespace App\Http\Livewire\DesignOrder;

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
        $this->emitTo('design-order.show-design-order', 'createModificationRequest', $this->modifications);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.design-order.request-modifications');
    }
}
