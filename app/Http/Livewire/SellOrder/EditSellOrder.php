<?php

namespace App\Http\Livewire\SellOrder;

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
use App\Notifications\RequestApproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSellOrder extends Component
{
    use WithFileUploads;
    
    public $open = false,
        $sell_order,
        $customer,
        $freight_currency,
        $oce,
        $oce_id,
        $edit_index,
        $original_customer_id,
        $deleted_message = 'Producto eliminado de la orden',
        $temporary_deleted_list = [],
        $sell_ordered_products_list = [];

    protected $rules = [
        'sell_order.shipping_company' => 'required|max:191',
        'sell_order.tracking_guide' => 'required',
        'sell_order.freight_cost' => 'required|max:191',
        'sell_order.contact_id' => 'required',
        'sell_order.priority' => 'required',
        'sell_order.order_via' => 'required',
        'sell_order.invoice' => 'required',
        'sell_order.notes' => 'max:250',
        'sell_order.oce_name' => 'max:191',
    ];

    protected $add_sell_ordered_products_rules = [
        'customer' => 'required',
    ];

    protected $store_aditional_rules = [
        'sell_ordered_products_list' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-sell-ordered-product' => 'selectedSellOrderedProduct',
        'updated-sell-ordered-product' => 'updatedSellOrderedProduct',
        'selected-customer' => 'selectedCustomer',
    ];

    public function mount()
    {
        $this->oce_id = rand();
        $this->sell_order = new SellOrder();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'sell_order',
            ]);
            $this->oce_id = rand();
        }
    }

    public function openModal(SellOrder $sell_order)
    {
        $this->open = true;
        $this->sell_order = $sell_order;
        $this->freight_currency = $this->sell_order->freightCurrency();
        $this->sell_order->freight_cost = explode('$', $this->sell_order->freight_cost)[0];
        foreach ($this->sell_order->sellOrderedProducts as $s_o_p) {
            $this->sell_ordered_products_list[] = $s_o_p->toArray();
        }
        $this->customer = Customer::find($sell_order->customer_id);

        if ($sell_order->authorized_user_id && $sell_order->status != 'Terminado') {
            $this->deleted_message = 'Si actualiza la OV, esta mercancía se regresará a inventario automáticamente';
        }
    }

    public function selectedSellOrderedProduct($sell_ordered_product)
    {
        $this->sell_ordered_products_list[] = $sell_ordered_product;
    }

    public function updatedSellOrderedProduct($sell_ordered_product)
    {
        $this->sell_ordered_products_list[$this->edit_index] = $sell_ordered_product;
    }

    public function authorize()
    {
        $success_message = '';
        $this->sell_order->authorized_user_id = Auth::user()->id;
        $this->sell_order->status = 'Sin iniciar';
        $this->sell_order->save();

        // notify to request's creator
        $this->sell_order->creator->notify(new RequestApproved('orden de venta', $this->sell_order->id, 'sell-orders'));

        /// create stock movements
        foreach ($this->sell_order->sellOrderedProducts as $sop) {
            // create stock movements (- out)
            // $this->_assignOperator($sop);
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
                        'notes' => "Para OV con ID {$this->sell_order->id}",
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
                    'notes' => "Para OV con ID {$this->sell_order->id}",
                    'user_id' => Auth::user()->id,
                    'stock_product_id' => $stock_product->id,
                    'stock_action_type_id' => 5, //venta
                ]);
                $stock_product->quantity = $stock_product->quantity - $quantity_needed;
                $stock_product->save();
                $success_message .= "<li class='list-disc ml-2 text-red-500'>Se restó mercancía del inventario <b>({$product->name})</b></li>";
            }
        }

        $this->resetExcept([
            'sell_order',
        ]);

        $this->emit('success', 'OV autorizada.' . $success_message);
        $this->emitTo('sell-order.sell-orders', 'render');
    }

    public function selectedCustomer(Customer $customer)
    {
        if ($this->customer) {
            if ($this->customer->id != $customer->id) {
                if ($customer->id == $this->original_customer_id) {
                    $this->temporary_deleted_list = [];
                } elseif (is_null($this->original_customer_id)) {
                    foreach ($this->sell_ordered_products_list as $t_d_p) {
                        $this->temporary_deleted_list[] = $t_d_p["id"];
                    }
                    $this->deleted_message = "Al cambiar de cliente, este producto no es válido";
                    $this->original_customer_id = $this->customer->id;
                }
            }
        }
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
            'sell-order.edit-sell-order'
        );
    }

    public function editItem($index)
    {
        $this->edit_index = $index;

        $this->emitTo(
            'sell-ordered-product.edit-sell-ordered-product',
            'openModal',
            new SellOrderedProduct($this->sell_ordered_products_list[$index]),
            'sell-order.edit-sell-order'
        );
    }

    public function addOperators($index)
    {
        $emit_to = 'user-has-sell-ordered-product.create-user-has-sell-ordered-product';
        $has_operators = false;

        $s_o_p_object = SellOrderedProduct::find($this->sell_ordered_products_list[$index]["id"]);
        if ($s_o_p_object->activityDetails()->count()) {
            $has_operators = true;
        }

        $this->emitTo(
            $emit_to,
            'openModal',
            $this->sell_ordered_products_list[$index],
            $has_operators
        );
    }

    public function addToTemporaryDeletedList($id)
    {
        $this->temporary_deleted_list[] = $id;
    }

    public function removeFromTemporaryDeletedList($id)
    {
        $index = array_search($id, $this->temporary_deleted_list);
        unset($this->temporary_deleted_list[$index]);
    }

    public function deleteItem($index)
    {
        if (array_key_exists('id', $this->sell_ordered_products_list[$index])) {
            $this->addToTemporaryDeletedList($this->sell_ordered_products_list[$index]["id"]);
        } else {
            unset($this->sell_ordered_products_list[$index]);
        }
    }

    public function update()
    {
        // check if there are 0 products registered (included temporary deleted)
        // send error validate message
        // $this->validate($this->store_aditional_rules, [
        //     'sell_ordered_products_list.required' => 'Debe de haber mínimo un producto agregado'
        // ]);
        $success_message = "";

        $this->validate();

        $this->sell_order->freight_cost .= $this->freight_currency;

        if ($this->oce) {
            Storage::delete($this->sell_order->oce);
            $this->sell_order->oce = $this->oce->store('files/OCEs', 'public');
        }

        $this->sell_order->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó orden de venta con ID: {$this->sell_order->id}"
        ]);

        // update new sell ordered products and update olds
        foreach ($this->sell_ordered_products_list as $s_o_p) {
            if (array_key_exists('id', $s_o_p)) {
                SellOrderedProduct::find($s_o_p["id"])
                    ->update($s_o_p);
            } else {
                $s_o_p["sell_order_id"] = $this->sell_order->id;
                $sop = SellOrderedProduct::create($s_o_p);
                // create stock movements (- outs)
                if ($this->sell_order->authorized_user_id && $this->sell_order->status != 'Terminado') {
                    $product_for_sell = $sop->productForSell;
                    if ($product_for_sell->model_name == CompositProduct::class) {
                        $composit_product = CompositProduct::find($product_for_sell->model_id);
                        foreach ($composit_product->compositProductDetails as $cpd) {
                            $stock_product = StockProduct::where('product_id', $cpd->product_id)->first();
                            $quantity_needed = $sop->quantity * $cpd->quantity;
                            StockMovement::create([
                                'quantity' => $quantity_needed,
                                'notes' => "Para OV con ID {$this->sell_order->id}",
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
                        $quantity_needed = $sop->quantity;
                        StockMovement::create([
                            'quantity' => $quantity_needed,
                            'notes' => "Para OV con ID {$this->sell_order->id}",
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
        }

        // delete old products on temporary list
        $sop_to_delete = SellOrderedProduct::whereIn('id', $this->temporary_deleted_list)->get();
        foreach ($sop_to_delete as $sop) {
            if ($this->sell_order->authorized_user_id && $this->sell_order->status != 'Terminado') {
                // create stock movements (+ in)
                $product_for_sell = $sop->productForSell;
                if ($product_for_sell->model_name == CompositProduct::class) {
                    $composit_product = CompositProduct::find($product_for_sell->model_id);
                    foreach ($composit_product->compositProductDetails as $cpd) {
                        $stock_product = StockProduct::where('product_id', $cpd->product_id)->first();
                        $quantity_needed = $sop->quantity * $cpd->quantity;
                        StockMovement::create([
                            'quantity' => $quantity_needed,
                            'notes' => "Cancelado de OV con ID {$this->sell_order->id}",
                            'user_id' => Auth::user()->id,
                            'stock_product_id' => $stock_product->id,
                            'stock_action_type_id' => 9, //cancelado de ov
                        ]);
                        $stock_product->quantity = $stock_product->quantity + $quantity_needed;
                        $stock_product->save();
                        $success_message .= "<li class='list-disc ml-2 text-green-500'>Se regresó mercancía al inventario por cancelación de <b>({$cpd->product->name})</b></li>";
                    }
                } else {
                    $product = Product::find($product_for_sell->model_id);
                    $stock_product = StockProduct::where('product_id', $product->id)->first();
                    $quantity_needed = $sop->quantity;
                    StockMovement::create([
                        'quantity' => $quantity_needed,
                        'notes' => "Cancelado de OV con ID {$this->sell_order->id}",
                        'user_id' => Auth::user()->id,
                        'stock_product_id' => $stock_product->id,
                        'stock_action_type_id' => 9, //cancelado de ov
                    ]);
                    $stock_product->quantity = $stock_product->quantity + $quantity_needed;
                    $stock_product->save();
                }
                $success_message .= "<li class='list-disc ml-2 text-green-500'>Se regresó mercancía al inventario por cancelación de <b>({$product->name})</b></li>";
            }
        }
        SellOrderedProduct::whereIn('id', $this->temporary_deleted_list)->delete();

        $this->resetExcept([
            'sell_order',
        ]);
        $this->oce_id = rand();

        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emit('success', 'Orden de venta actualizada.'.$success_message);
    }

    public function render()
    {
        return view('livewire.sell-order.edit-sell-order', [
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
