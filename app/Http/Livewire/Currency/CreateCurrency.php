<?php

namespace App\Http\Livewire\Currency;

use App\Models\Currency;
use Livewire\Component;

class CreateCurrency extends Component
{
    public $open = false,
        $name;

    protected $rules = [
        'name' => 'required|max:10',
    ];
    protected $listeners = [
        'openModal'
    ];

    public function store()
    {
        $this->validate();

        Currency::create([
            'name'  =>  $this->name
        ]);

        $this->reset();

        $this->emitTo('quote.create-quote', 'render');
        $this->emitTo('quote.edit-quote', 'render');
        $this->emit('success', 'Nueva moneda agregada');
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.currency.create-currency');
    }
}
