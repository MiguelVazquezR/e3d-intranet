<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Mail\ApproveMailable;
use App\Models\MovementHistory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderedProduct;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePurchaseOrder extends Component
{
    use WithFileUploads;

    public $open = false,
        $shipping_company,
        $tracking_guide = 'Colocar más tarde',
        $supplier,
        $contact_id,
        $notes,
        $edit_index,
        $iva_included = 0,
        $expected_delivery_at,
        $purchase_ordered_products_list = [];

    protected $rules = [
        'shipping_company' => 'required|max:191',
        'tracking_guide' => 'required',
        'contact_id' => 'required',
        'iva_included' => 'min:0',
        'expected_delivery_at' => 'required',
    ];

    protected $store_aditional_rules = [
        'purchase_ordered_products_list' => 'required',
    ];

    protected $listeners = [
        'render',
        'selected-supplier' => 'selectedSupplier',
        'selected-purchase-ordered-product' => 'selectedPurchaseOrderedProduct',
        'updated-purchase-ordered-product' => 'updatedPurchaseOrderedProduct',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function selectedPurchaseOrderedProduct($purchase_ordered_product)
    {
        $this->purchase_ordered_products_list[] = $purchase_ordered_product;
    }

    public function updatedPurchaseOrderedProduct($purchase_ordered_product)
    {
        $this->purchase_ordered_products_list[$this->edit_index] = $purchase_ordered_product;
    }

    public function selectedSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function addPurchaseOrderedProducts()
    {
        $this->emitTo(
            'purchase-ordered-product.create-purchase-ordered-product',
            'openModal',
            'purchase-order.create-purchase-order'
        );
    }

    public function editItem($index)
    {
        $this->edit_index = $index;
        $this->emitTo(
            'purchase-ordered-product.edit-purchase-ordered-product',
            'openModal',
            'purchase-order.create-purchase-order',
            $this->purchase_ordered_products_list[$index]
        );
    }

    public function deleteItem($index)
    {
        unset($this->purchase_ordered_products_list[$index]);
    }

    public function store()
    {
        $this->validate($this->store_aditional_rules, [
            'purchase_ordered_products_list.required' => 'Debe de haber mínimo un producto agregado'
        ]);

        $validated_data = $this->validate();

        $aditional_data = [
            'supplier_id' => $this->supplier->id,
            'notes' => $this->notes,
            'user_id' => Auth::user()->id,
        ];

        $purchase_order = PurchaseOrder::create($validated_data + $aditional_data);

        foreach ($this->purchase_ordered_products_list as $p_o_p) {
            $p_o_p["purchase_order_id"] = $purchase_order->id;
            PurchaseOrderedProduct::create($p_o_p);
        }

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se creó nueva orden de compra con ID: {$purchase_order->id}"
        ]);


        if (Auth::user()->can('autorizar_ordenes_compra')) {
            $purchase_order->authorized_user_id = Auth::user()->id;
            $purchase_order->status = 'Autorizado. Enviar a proveedor';
        } else {
            // send email notification
            Mail::to('maribel@emblemas3d.com')
                // ->bcc('miguelvz26.mv@gmail.com')
                ->queue(new ApproveMailable('Orden de compra', $purchase_order->id, PurchaseOrder::class));
        }

        $purchase_order->save();

        $this->reset();

        $this->emitTo('purchase-order.purchase-orders', 'render');
        $this->emit('success', 'Nueva orden de compra creada.');
    }

    public function render()
    {
        return view('livewire.purchase-order.create-purchase-order');
    }
}
