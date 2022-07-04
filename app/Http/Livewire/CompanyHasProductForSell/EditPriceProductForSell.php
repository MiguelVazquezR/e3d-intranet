<?php

namespace App\Http\Livewire\CompanyHasProductForSell;

use App\Models\Company;
use App\Models\CompanyHasProductForSell;
use App\Models\Currency;
use Carbon\Carbon;
use Livewire\Component;

class EditPriceProductForSell extends Component
{
    public $product_for_sell,
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

    // public function mount()
    // {
    //     $this->product_for_sell = new CompanyHasProductForSell();
    // }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal(CompanyHasProductForSell $product_for_sell)
    {
        $this->product_for_sell = $product_for_sell;
        $this->open = true;
    }

    public function update()
    {
        $this->product_for_sell->old_date = $this->product_for_sell->new_date;
        $this->product_for_sell->old_price = $this->product_for_sell->new_price;
        $this->product_for_sell->old_price_currency = $this->product_for_sell->new_price_currency;
        
        $this->product_for_sell->new_price = $this->new_price;
        $this->product_for_sell->new_price_currency = $this->new_price_currency;

        // date_default_timezone_set('America/Mexico_City');
        $this->product_for_sell->new_date = Carbon::now()->isoFormat('YYYY-M-D h:mm:ss');

        $this->product_for_sell->save();
        
        
        $this->emitTo('customer.edit-customer', 'openModal', Company::find($this->product_for_sell->company_id));
        $this->emit('success', 'Precio actualizado');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.company-has-product-for-sell.edit-price-product-for-sell', [
            'currencies' => Currency::all(),
        ]);
    }
}
