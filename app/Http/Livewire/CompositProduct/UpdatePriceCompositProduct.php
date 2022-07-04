<?php

namespace App\Http\Livewire\CompositProduct;

use App\Models\CompositProduct;
use App\Models\Currency;
use Carbon\Carbon;
use Livewire\Component;

class UpdatePriceCompositProduct extends Component
{
    public $composit_product,
        $open = false,
        $new_price,
        $new_price_currency = '$MXN';

    protected $rules = [
        'old_price' => 'required|numeric',
        'old_price_currency' => 'required',
    ];

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->composit_product = new CompositProduct();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'composit_product',
            ]);
        }
    }

    public function openModal(CompositProduct $composit_product)
    {
        $this->composit_product = $composit_product;
        $this->open = true;
    }

    public function update()
    {
        $this->composit_product->old_date = $this->composit_product->new_date;
        $this->composit_product->old_price = $this->composit_product->new_price;
        $this->composit_product->old_price_currency = $this->composit_product->new_price_currency;
        
        $this->composit_product->new_price = $this->new_price;
        $this->composit_product->new_price_currency = $this->new_price_currency;

        date_default_timezone_set('America/Mexico_City');
        $this->composit_product->new_date = Carbon::now()->isoFormat('YYYY-M-D h:mm:ss');

        $this->composit_product->save();
        
        $this->resetExcept([
            'composit_product',
        ]);
        $this->emitTo('customer.edit-customer', 'openModal', $this->composit_product->company);
        $this->emit('success', 'Precio actualizado');
    }

    public function render()
    {
        return view('livewire.composit-product.update-price-composit-product', [
            'currencies' => Currency::all(),
        ]);
    }
}
