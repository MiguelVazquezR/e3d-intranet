<?php

namespace App\Http\Livewire\SellOrderedProduct;

use App\Models\SellOrderedProduct;
use Livewire\Component;

class EditSellOrderedProduct extends Component
{
    public $open = false,
        $sell_ordered_product,
        $emit_response_to,
        $product_for_sell;

    protected $rules = [
        'sell_ordered_product.quantity' => 'required|numeric',
        'sell_ordered_product.notes' => 'max:200',
        'sell_ordered_product.new_design' => 'min:0',
        'sell_ordered_product.for_sell' => 'min:0',
        'sell_ordered_product.company_has_product_for_sell_id' => 'required',
    ];

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->sell_ordered_product = new SellOrderedProduct();
    }
    
    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }
    
    public function openModal($sell_ordered_product, $emit_response_to)
    {
        // add id key to avoid duplicates when editing registered product
        if( array_key_exists('id', $sell_ordered_product) ) {
            $this->rules['sell_ordered_product.id'] = 'required';
        }

        $this->open = true;
        $this->emit_response_to = $emit_response_to;
        $this->sell_ordered_product = new SellOrderedProduct($sell_ordered_product);
    }

    public function update()
    {
        $this->validate();

        $this->emitTo(
            $this->emit_response_to,
            'updated-sell-ordered-product',
            $this->sell_ordered_product
        );
        
        $this->reset('open');
    }

    public function render()
    {
        return view('livewire.sell-ordered-product.edit-sell-ordered-product');
    }
}
