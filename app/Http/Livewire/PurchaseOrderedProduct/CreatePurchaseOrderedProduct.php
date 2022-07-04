<?php

namespace App\Http\Livewire\PurchaseOrderedProduct;

use App\Models\Product;
use App\Models\PurchaseOrderedProduct;
use Livewire\Component;

class CreatePurchaseOrderedProduct extends Component
{
    public $open = false,
        $quantity,
        $emit_response_to,
        $product_for_buy,
        $code,
        $notes;

    protected $rules = [
        'quantity' => 'required|numeric',
        'product_for_buy' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-product' => 'selectedProduct',
    ];
    
    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }
    
    public function openModal($emit_response_to)
    {
        $this->open = true;
        $this->emit_response_to = $emit_response_to;
    }

    public function selectedProduct(Product $selection)
    {
        $this->product_for_buy = $selection;
    }
    
    public function store()
    {
        $this->validate();

        $purchase_ordered_product = new PurchaseOrderedProduct([
            'quantity' => $this->quantity,
            'code' => $this->code,
            'notes' => $this->notes,
            'product_id' => $this->product_for_buy->id,
        ]);

        $this->emitTo($this->emit_response_to,'selected-purchase-ordered-product', $purchase_ordered_product->toArray());
        
        $this->reset();
    }

    public function render()
    {
        return view('livewire.purchase-ordered-product.create-purchase-ordered-product');
    }
}
