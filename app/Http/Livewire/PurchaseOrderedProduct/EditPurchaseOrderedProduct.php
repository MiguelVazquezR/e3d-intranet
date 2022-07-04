<?php

namespace App\Http\Livewire\PurchaseOrderedProduct;

use App\Models\Product;
use App\Models\PurchaseOrderedProduct;
use Livewire\Component;

class EditPurchaseOrderedProduct extends Component
{
    public $open = false,
        $quantity,
        $emit_response_to,
        $product_for_buy,
        $purchase_ordered_product,
        $notes;

    protected $rules = [
        'purchase_ordered_product.quantity' => 'required|numeric',
        'purchase_ordered_product.notes' => 'max:800',
        'purchase_ordered_product.code' => 'max:191',
        'product_for_buy' => 'required',
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
    
    public function openModal($emit_response_to, $purchase_ordered_product)
    {
        $this->open = true;
        $this->emit_response_to = $emit_response_to;
        $this->purchase_ordered_product = $purchase_ordered_product;
        $this->product_for_buy = Product::find($purchase_ordered_product["product_id"]);
    }

    public function update()
    {
        $this->validate();

        $this->emitTo($this->emit_response_to,'updated-purchase-ordered-product', $this->purchase_ordered_product);
        $this->reset();
    }


    public function render()
    {
        return view('livewire.purchase-ordered-product.edit-purchase-ordered-product');
    }
}
