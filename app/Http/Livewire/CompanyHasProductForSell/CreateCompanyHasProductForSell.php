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

class CreateCompanyHasProductForSell extends Component
{
    public $company,
        $open = false,
        $old_date,
        $new_date,
        $old_price,
        $new_price,
        $old_price_currency = '$MXN',
        $new_price_currency = '$MXN',
        $edit_index = null,
        $selected_product,
        $simple_product = 0,
        $products_list = [];

    protected $rules = [
        'new_date' => 'required',
        'new_price' => 'required|numeric',
        'new_price_currency' => 'required',
        'old_date' => 'required',
        'old_price' => 'required|numeric',
        'old_price_currency' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-product' => 'selectedProduct',
        'selected-composit-product' => 'selectedCompositProduct',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'company',
            ]);
        }
    }

    public function openModal(Company $company)
    {
        $this->company = $company;
        $this->open = true;
    }

    public function selectedProduct(Product $selection)
    {
        $this->selected_product = $selection;
    }

    public function selectedCompositProduct(CompositProduct $selection)
    {
        $this->selected_product = $selection;
    }

    public function addItemToList()
    {
        $validated_data = $this->validate();

        $product_for_sell = new CompanyHasProductForSell(
            [
                'model_id' => $this->selected_product->id,
            ] + $validated_data
        );
        if ($this->selected_product instanceof Product) {
            $product_for_sell->model_name =  Product::class;
        } else {
            $product_for_sell->model_name = CompositProduct::class;
        }

        $this->products_list[] = $product_for_sell->toArray();

        $this->resetItem();
    }

    public function resetItem()
    {
        $this->reset([
            'old_date',
            'new_date',
            'old_price',
            'new_price',
            'old_price_currency',
            'new_price_currency',
            'edit_index',
            'selected_product',
        ]);
    }

    public function editItem($index)
    {
        if ($this->products_list[$index]["model_name"] == Product::class) {
            $this->selected_product =
                Product::find($this->products_list[$index]["model_id"]);
        } else {
            $this->selected_product =
                CompositProduct::find($this->products_list[$index]["model_id"]);
        }

        $this->new_date = Carbon::parse($this->products_list[$index]["new_date"])->isoFormat('YYYY-MM-D hh:mm:ss');        
        $this->new_price = $this->products_list[$index]["new_price"];
        $this->new_price_currency = $this->products_list[$index]["new_price_currency"];
        $this->old_date = Carbon::parse($this->products_list[$index]["old_date"])->isoFormat('YYYY-MM-D');
        $this->old_price = $this->products_list[$index]["old_price"];
        $this->old_price_currency = $this->products_list[$index]["old_price_currency"];
        $this->edit_index = $index;
    }

    public function updateItem()
    {
        $validated_data = $this->validate();

        $product_for_sell = new CompanyHasProductForSell(
            [
                'model_id' => $this->selected_product->id,
            ] + $validated_data
        );
        if ($this->selected_product instanceof Product) {
            $product_for_sell->model_name =  Product::class;
        } else {
            $product_for_sell->model_name = CompositProduct::class;
        }

        $this->products_list[$this->edit_index] = $product_for_sell->toArray();

        $this->resetItem();
    }

    public function deleteItem($index)
    {
        unset($this->products_list[$index]);
    }

    public function store()
    {
        $this->validate(['products_list' => 'required'], [
            'products_list.required' => 'Debe de haber mínimo un producto agregado a la lista para registrar'
        ]);

        // create all assigned operators to ordered product and update olds
        foreach ($this->products_list as $product_for_sell) {
            $product_for_sell["new_date"] = Carbon::parse($product_for_sell["new_date"])->isoFormat('YYYY-MM-D hh:mm:ss');
            $product_for_sell["old_date"] = Carbon::parse($product_for_sell["old_date"])->isoFormat('YYYY-MM-D hh:mm:ss');
            $product_for_sell["company_id"] = $this->company->id;
            $chpfs = CompanyHasProductForSell::create($product_for_sell);

            // create movement history
            MovementHistory::create([
                'movement_type' => 1,
                'user_id' => Auth::user()->id,
                'description' => "Se registró producto para venta a la empresa {$chpfs->company->bussiness_name}"
            ]);
        }

        $this->resetExcept([
            'company'
        ]);

        $this->emitTo('customer.edit-customer', 'openModal', $this->company);
        $this->emit('success', 'Producto para venta agregado');
    }

    public function render()
    {
        return view('livewire.company-has-product-for-sell.create-company-has-product-for-sell', [
            'currencies' => Currency::all(),
        ]);
    }
}
