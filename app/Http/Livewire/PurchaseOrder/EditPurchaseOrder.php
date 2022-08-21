<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\MovementHistory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderedProduct;
use App\Models\Supplier;
use App\Notifications\RequestApproved;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditPurchaseOrder extends Component
{
    public $open = false,
        $purchase_order,
        $supplier,
        $expected_delivery_at,
        $edit_index,
        $original_supplier_id,
        $deleted_message = 'Producto eliminado de la orden',
        $temporary_deleted_list = [],
        $purchase_ordered_products_list = [];

    protected $rules = [
        'purchase_order.shipping_company' => 'required|max:191',
        'purchase_order.tracking_guide' => 'required',
        'purchase_order.contact_id' => 'required',
        'purchase_order.notes' => 'max:250',
    ];

    protected $store_aditional_rules = [
        'purchase_ordered_products_list' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-purchase-ordered-product' => 'selectedPurchaseOrderedProduct',
        'updated-purchase-ordered-product' => 'updatedPurchaseOrderedProduct',
    ];

    public function mount()
    {
        $this->purchase_order = new PurchaseOrder();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'purchase_order',
            ]);
        }
    }

    public function openModal(PurchaseOrder $purchase_order)
    {
        $this->open = true;
        $this->purchase_order = $purchase_order;

        foreach ($this->purchase_order->purchaseOrderedProducts as $p_o_p) {
            $this->purchase_ordered_products_list[] = $p_o_p->toArray();
        }
        $this->supplier = Supplier::find($purchase_order->supplier_id);

        $this->expected_delivery_at = $this->purchase_order->expected_delivery_at->isoFormat('YYYY-MM-DD');
    }

    public function selectedPurchaseOrderedProduct($purchase_ordered_product)
    {
        $this->purchase_ordered_products_list[] = $purchase_ordered_product;
    }

    public function updatedPurchaseOrderedProduct($purchase_ordered_product)
    {
        $this->purchase_ordered_products_list[$this->edit_index] = $purchase_ordered_product;
    }

    public function authorize()
    {
        // $success_message = '';
        $this->purchase_order->authorized_user_id = Auth::user()->id;
        $this->purchase_order->status = 'Autorizado. Enviar a proveedor';
        $this->purchase_order->save();

        // notify to request's creator
        $this->purchase_order->creator->notify(new RequestApproved('orden de compra', $this->purchase_order->id, 'purchase-orders'));

        $this->resetExcept([
            'purchase_order',
        ]);

        $this->emit('success', 'OC autorizada.');
        $this->emitTo('purchase-order.purchase-orders', 'render');
    }

    public function addPurchaseOrderedProducts()
    {
        $this->emitTo(
            'purchase-ordered-product.create-purchase-ordered-product',
            'openModal',
            'purchase-order.edit-purchase-order'
        );
    }

    public function editItem($index)
    {
        $this->edit_index = $index;
        $this->emitTo(
            'purchase-ordered-product.edit-purchase-ordered-product',
            'openModal',
            'purchase-order.edit-purchase-order',
            $this->purchase_ordered_products_list[$index]
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
        if (array_key_exists('id', $this->purchase_ordered_products_list[$index])) {
            $this->addToTemporaryDeletedList($this->purchase_ordered_products_list[$index]["id"]);
        } else {
            unset($this->purchase_ordered_products_list[$index]);
        }
    }

    public function update()
    {
        // check if there are 0 products registered (included temporary deleted)
        // send error validate message
        // $this->validate($this->store_aditional_rules, [
        //     'purchase_ordered_products_list.required' => 'Debe de haber mínimo un producto agregado'
        // ]);

        $this->validate();

        $this->purchase_order->expected_delivery_at = $this->expected_delivery_at;
        $this->purchase_order->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó orden de compra con ID: {$this->purchase_order->id}"
        ]);

        // update new purchase ordered products and update olds
        foreach ($this->purchase_ordered_products_list as $p_o_p) {
            if (array_key_exists('id', $p_o_p)) {
                PurchaseOrderedProduct::find($p_o_p["id"])
                    ->update($p_o_p);
            } else {
                $p_o_p["purchase_order_id"] = $this->purchase_order->id;
                $pop = purchaseOrderedProduct::create($p_o_p);
            }
        }

        // delete old products on temporary list
        PurchaseOrderedProduct::whereIn('id', $this->temporary_deleted_list)->delete();

        $this->resetExcept([
            'purchase_order',
        ]);

        $this->emitTo('purchase-order.purchase-orders', 'render');
        $this->emit('success', 'Orden de compra actualizada.');
    }

    public function render()
    {
        return view('livewire.purchase-order.edit-purchase-order');
    }
}
