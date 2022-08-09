<?php

namespace App\Http\Livewire\Quote;

use App\Mail\ApproveMailable;
use App\Models\Company;
use App\Models\CompanyHasProductForSell;
use App\Models\CompositProduct;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\Quote;
use App\Models\SellOrder;
use App\Models\SellOrderedProduct;
use App\Models\UserHasSellOrderedProduct;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class TurnIntoSellOrder extends Component
{
    use WithFileUploads;

    public $open = false,
        $quote,
        $shipping_company,
        $freight_cost,
        $freight_currency,
        $tracking_guide = 'Colocar más tarde',
        $customer,
        $contact_id,
        $priority = 'Normal',
        $oce_name,
        $oce,
        $oce_id,
        $order_via = "Cotización ID:",
        $invoice = 'Colocar más tarde',
        $notes,
        $products_for_sell_list = [],
        $sell_ordered_products_list = [];

    protected $rules = [
        'shipping_company' => 'required|max:191',
        'tracking_guide' => 'required',
        'freight_cost' => 'required|max:191',
        'contact_id' => 'required',
        'priority' => 'required',
        'order_via' => 'required',
        'invoice' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function mount()
    {
        $this->oce_id = rand();
        $this->quote = new Quote;
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
            $this->oce_id = rand();
        }
    }

    public function openModal(Quote $quote)
    {
        $this->open = true;
        $this->quote = $quote;
        $this->order_via .= $quote->id;

        // products for sell
        $currency = Currency::find($this->quote->currency_id)->name;
        $products = $quote->quotedCompositProducts;
        foreach ($products as $product) {
            $this->products_for_sell_list[] = [
                'model_id' => $product->composit_product_id,
                'model_name' => CompositProduct::class,
                'new_date' => date('Y-m-d H:i:s'),
                'new_price' => $product->price,
                'new_price_currency' => $currency,
                'quantity' => $product->quantity,
            ];
        }

        $products = $this->quote->quotedProducts;
        foreach ($products as $product) {
            $this->products_for_sell_list[] = [
                'model_id' => $product->product_id,
                'model_name' => Product::class,
                'new_date' => now(),
                'new_price' => $product->price,
                'new_price_currency' => $currency,
                'quantity' => $product->quantity,
            ];
        }

        // sell ordered products
        foreach ($this->products_for_sell_list as $product) {
            $this->sell_ordered_products_list[] = [
                'quantity' => $product["quantity"],
                'for_sell' => 1,
                'new_design' => 0,
            ];
        }
    }

    public function store()
    {
        $success_message = '';

        if (is_null($this->quote->customer_id)) {
            $this->customer = $this->_createCustomer();
            $this->contact_id = $this->_createContact();
            $success_message .= "Se agregó nuevo cliente (ID: {$this->customer->id}), Favor de ir a completar campos importantes";
        } else {
            $this->customer = $this->quote->customer;
        }

        if ($this->oce_name) {
            $this->rules['oce'] = 'required';
        }

        $validated_data = $this->validate();

        $aditional_data = [
            'customer_id' => $this->customer->id,
            'notes' => $this->notes,
            'user_id' => auth()->user()->id,
        ];

        $sell_order = SellOrder::create($validated_data + $aditional_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => auth()->user()->id,
            'description' => "Se creó nueva orden de venta con ID: {$sell_order->id} desde cotización con ID: {$this->quote->id}"
        ]);

        $sell_order->freight_cost = $this->freight_cost . $this->freight_currency;
        $sell_order->oce_name = $this->oce_name;

        if ($this->oce) {
            $sell_order->oce = $this->oce->store('files/OCEs', 'public');
        }

        if (auth()->user()->can('autorizar_ordenes_venta')) {
            $sell_order->authorized_user_id = auth()->user()->id;
            $sell_order->status = 'Sin iniciar';
        } else {
            // send email notification
            if (App::environment('production'))
                Mail::to('maribel@emblemas3d.com')
                    // ->bcc('miguelvz26.mv@gmail.com')
                    ->queue(new ApproveMailable('Orden de venta', $sell_order->id, SellOrder::class));
        }

        $sell_order->save();

        // create sell ordered products & stock movement
        foreach ($this->sell_ordered_products_list as $i => $s_o_p) {
            // create product for sell
            unset($this->products_for_sell_list[$i]["quantity"]);
            $this->products_for_sell_list[$i]["company_id"] = $this->customer->company->id;
            $pfs = CompanyHasProductForSell::create($this->products_for_sell_list[$i]);

            // create sell ordered product
            $s_o_p["sell_order_id"] = $sell_order->id;
            $s_o_p["company_has_product_for_sell_id"] = $pfs->id;
            $sop = SellOrderedProduct::create($s_o_p);

            if ($sell_order->authorized_user_id) {
                // assign operator
                $this->_assignOperator($sop);
            }
        }

        $this->quote->update(['sell_order_id' => $sell_order->id]);

        $this->resetExcept(['quote']);
        $this->oce = rand();

        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emit('success', 'Nueva orden de venta creada. ' . $success_message);
        $this->emitTo('quote.quotes', 'render');
    }

    public function render()
    {
        return view('livewire.quote.turn-into-sell-order', [
            'currencies' => Currency::all(),
        ]);
    }

    // protected methods ----------------------------------
    protected function _assignOperator($sell_ordered_product)
    {
        UserHasSellOrderedProduct::create([
            'estimated_time' => $sell_ordered_product->getEstimatedTime(),
            'indications' => 'Asignado automáticamente',
            'sell_ordered_product_id' => $sell_ordered_product->id,
            'user_id' => Employee::getAvailableOperator()->id
        ]);
    }

    protected function _createCustomer()
    {
        $company = Company::create([
            'bussiness_name' => $this->quote->customer_name,
            'phone' => '1',
            'rfc' => 'COLOCAR !',
            'post_code' => 1,
            'fiscal_address' => 'COLOCAR !',
        ]);

        return Customer::create([
            'name' => $this->quote->customer_name,
            'address' => 'COLOCAR !',
            'post_code' => 1,
            'sat_method_id' => 1,
            'sat_type_id' => 1,
            'sat_way_id' => 1,
            'company_id' => $company->id,
        ]);
    }

    protected function _createContact()
    {
        return Contact::create([
            'contactable_id' => $this->customer->id,
            'contactable_type' => Customer::class,
            'name' => $this->quote->receiver,
            'email' => 'COLOCAR !',
            'phone' => '1',
        ])->id;
    }
}
