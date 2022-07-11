<?php

namespace App\Http\Livewire\SellOrder;

use App\Mail\ApproveMailable;
use App\Models\CompositProduct;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\SellOrder;
use App\Models\SellOrderedProduct;
use App\Models\StockMovement;
use App\Models\StockProduct;
use App\Models\UserHasSellOrderedProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSellOrder extends Component
{
    use WithFileUploads;

    public $open = false,
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
        $order_via,
        $invoice = 'Colocar más tarde',
        $notes,
        $edit_index,
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

    protected $add_sell_ordered_products_rules = [
        'customer' => 'required',
    ];

    protected $store_aditional_rules = [
        'sell_ordered_products_list' => 'required',
    ];

    protected $listeners = [
        'render',
        'selected-sell-ordered-product' => 'selectedSellOrderedProduct',
        'updated-sell-ordered-product' => 'updatedSellOrderedProduct',
        'selected-customer' => 'selectedCustomer',
    ];

    public function mount()
    {
        $this->oce_id = rand();
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

    public function openModal()
    {
        $this->open = true;
    }

    public function selectedSellOrderedProduct($sell_ordered_product)
    {
        $this->sell_ordered_products_list[] = $sell_ordered_product;
    }

    public function updatedSellOrderedProduct($sell_ordered_product)
    {
        $this->sell_ordered_products_list[$this->edit_index] = $sell_ordered_product;
    }

    public function selectedCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function addSellOrderedProducts()
    {
        $this->validate($this->add_sell_ordered_products_rules, [
            'customer.required' => 'Para agregar un producto, primero seleccione al cliente'
        ]);
        $this->emitTo(
            'sell-ordered-product.create-sell-ordered-product',
            'openModal',
            $this->customer->company,
            'sell-order.create-sell-order'
        );
    }

    public function editItem($index)
    {
        $this->edit_index = $index;

        $this->emitTo(
            'sell-ordered-product.edit-sell-ordered-product',
            'openModal',
            new SellOrderedProduct($this->sell_ordered_products_list[$index]),
            'sell-order.create-sell-order'
        );
    }

    public function deleteItem($index)
    {
        unset($this->sell_ordered_products_list[$index]);
    }

    public function store()
    {
        $success_message = '';

        if ($this->oce_name) {
            $this->rules['oce'] = 'required';
        }

        $this->validate($this->store_aditional_rules, [
            'sell_ordered_products_list.required' => 'Debe de haber mínimo un producto agregado'
        ]);

        $validated_data = $this->validate();

        $aditional_data = [
            'customer_id' => $this->customer->id,
            'notes' => $this->notes,
            'user_id' => Auth::user()->id,
        ];

        $sell_order = SellOrder::create($validated_data + $aditional_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se creó nueva orden de venta con ID: {$sell_order->id}"
        ]);

        $sell_order->freight_cost = $this->freight_cost . $this->freight_currency;
        $sell_order->oce_name = $this->oce_name;

        if ($this->oce) {
            $sell_order->oce = $this->oce->store('files/OCEs', 'public');
        }

        if (Auth::user()->can('autorizar_ordenes_venta')) {
            $sell_order->authorized_user_id = Auth::user()->id;
            $sell_order->status = 'Sin iniciar';
        } else {
            // send email notification
            Mail::to('maribel@emblemas3d.com')
                // ->bcc('miguelvz26.mv@gmail.com')
                ->queue(new ApproveMailable('Orden de venta', $sell_order->id, SellOrder::class));
        }

        $sell_order->save();

        // create sell ordered products & stock movement
        foreach ($this->sell_ordered_products_list as $s_o_p) {
            $s_o_p["sell_order_id"] = $sell_order->id;
            $sop = SellOrderedProduct::create($s_o_p);

            if (auth()->user()->can('autorizar_ordenes_venta'))
                $this->_assignOperator($sop);

            if ($sell_order->authorized_user_id) {
                // create stock movements
                $product_for_sell = $sop->productForSell;
                if ($product_for_sell->model_name == CompositProduct::class) {
                    $composit_product = CompositProduct::find($product_for_sell->model_id);
                    foreach ($composit_product->compositProductDetails as $cpd) {
                        $stock_product = StockProduct::where('product_id', $cpd->product_id)->first();
                        if (!$stock_product) {
                            $stock_product = $this->_createNewStockProduct($cpd->product);
                        }
                        $quantity_needed = $sop->quantity * $cpd->quantity;
                        StockMovement::create([
                            'quantity' => $quantity_needed,
                            'notes' => "Para OV con ID $sell_order->id",
                            'user_id' => Auth::user()->id,
                            'stock_product_id' => $stock_product->id,
                            'stock_action_type_id' => 5, //venta
                        ]);
                        $stock_product->quantity = $stock_product->quantity - $quantity_needed;
                        $stock_product->save();
                        $success_message .= "<li class='list-disc ml-2 text-red-500'>Se restó mercancía del inventario <b>({$cpd->product->name})</b></li>";
                    }
                } else {
                    $product = Product::find($product_for_sell->model_id);
                    $stock_product = StockProduct::where('product_id', $product->id)->first();
                    if (!$stock_product) {
                        $stock_product = $this->_createNewStockProduct($product);
                    }
                    $quantity_needed = $sop->quantity;
                    StockMovement::create([
                        'quantity' => $quantity_needed,
                        'notes' => "Para OV con ID $sell_order->id",
                        'user_id' => Auth::user()->id,
                        'stock_product_id' => $stock_product->id,
                        'stock_action_type_id' => 5, //venta
                    ]);
                    $stock_product->quantity = $stock_product->quantity - $quantity_needed;
                    $stock_product->save();
                    $success_message .= "<li class='list-disc ml-2 text-red-500'>Se restó mercancía del inventario <b>({$product->name})</b></li>";
                }
            }
        }

        $this->reset();
        $this->oce = rand();

        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emit('success', 'Nueva orden de venta creada. ' . $success_message);
    }

    public function render()
    {
        return view('livewire.sell-order.create-sell-order', [
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

    protected function _createNewStockProduct(Product $product)
    {
        return StockProduct::create([
            'product_id'  =>  $product->id,
            'location'  =>  'Por definir',
            'quantity'  =>  0,
            'image'  => 'public/stock_products/default.jpg',
        ]);
    }
}
