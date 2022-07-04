<?php

namespace App\Http\Livewire\CompanyHasProductForSell;

use App\Models\Company;
use App\Models\CompanyHasProductForSell;
use App\Models\CompositProduct;
use App\Models\Currency;
use App\Models\MovementHistory;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditCompanyHasProductForSell extends Component
{
    public
        $open = false,
        $old_date,
        $new_date,
        $product_for_sell;

    protected $rules = [
        'new_date' => 'required',
        'old_date' => 'required',
        'product_for_sell.new_price' => 'required|numeric',
        'product_for_sell.new_price_currency' => 'required',
        'product_for_sell.old_price' => 'required|numeric',
        'product_for_sell.old_price_currency' => 'required',
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

    public function openModal(CompanyHasProductForSell $product_for_sell)
    {
        $this->product_for_sell = $product_for_sell;
        $this->old_date = $product_for_sell->old_date->isoFormat('YYYY-MM-D');
        $this->new_date = $product_for_sell->new_date->isoFormat('YYYY-MM-D');
        $this->open = true;
    }

    public function update()
    {
        $this->validate();

        $this->product_for_sell->old_date = Carbon::parse($this->old_date)->isoFormat('YYYY-MM-D hh:mm:ss');
        $this->product_for_sell->new_date = Carbon::parse($this->new_date)->isoFormat('YYYY-MM-D hh:mm:ss');

        $this->product_for_sell->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó producto para venta a la empresa {$this->product_for_sell->company->bussiness_name}"
        ]);
        
        $this->emitTo('customer.edit-customer', 'openModal', Company::find($this->product_for_sell->company_id));
        $this->emit('success', 'Producto para venta actualizado');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.company-has-product-for-sell.edit-company-has-product-for-sell', [
            'currencies' => Currency::all(),
        ]);
    }
}
