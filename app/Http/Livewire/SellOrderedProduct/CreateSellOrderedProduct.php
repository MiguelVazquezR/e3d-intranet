<?php

namespace App\Http\Livewire\SellOrderedProduct;

use App\Models\Company;
use App\Models\CompanyHasProductForSell;
use App\Models\CompositProduct;
use App\Models\Product;
use App\Models\SellOrderedProduct;
use App\Models\StockProduct;
use Livewire\Component;

class CreateSellOrderedProduct extends Component
{
    public $open = false,
        $quantity,
        $for_sell = 1,
        $new_design = 0,
        $emit_response_to,
        $company,
        $product_for_sell,
        $low_stock_messages = [],
        $no_stock_record_messages = [],
        $first_production_messages = [],
        $notes;

    protected $rules = [
        'quantity' => 'required|numeric',
        'product_for_sell' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function updatedQuantity($quantity)
    {
        $this->reset([
            'low_stock_messages',
            'no_stock_record_messages',
        ]);
        // add alert messages if low stock while type quantity
        $product_for_sell = CompanyHasProductForSell::find($this->product_for_sell);
        if($product_for_sell->model_name == CompositProduct::class) {
            $composit_product = CompositProduct::find($product_for_sell->model_id);
            foreach($composit_product->compositProductDetails as $cpd) {
                if($cpd->product->product_status_id == 3) {
                    $this->first_production_messages [] = 
                    "<b>{$cpd->product->name}</b> Se creará automáticamente el registro de este producto en inventario, pero no olvide cambiar status a <b>Activo</b> desde el módulo de productos simples y ajustar las existencias físicas del inventario.";
                } else {
                    $stock_product = StockProduct::where('product_id', $cpd->product_id)->first();
                    if($stock_product) {
                        $quantity_needed = $quantity * $cpd->quantity;
                        if($stock_product->quantity < $quantity_needed) {
                            $this->low_stock_messages [] = 
                            "Hay $stock_product->quantity {$stock_product->product->unit->name} de <b>{$stock_product->product->name}</b>, se requieren $quantity_needed. Reponer para cumplir con la orden de venta a tiempo";
                        }
                    } else {
                        $this->no_stock_record_messages [] = 
                        "Se creará automáticamente el registro de <b>{$cpd->product->name}</b> en inventario ya que no existe actualmente. Pero recuerda ajustar las existencias físicas del inventario";
                    }
                }
            }
        } else {
            $product = Product::find($product_for_sell->model_id);
            if($product->product_status_id == 3) {
                $this->first_production_messages [] = 
                    "<b>{$product->name}</b> Se creará automáticamente el registro de este producto en inventario, pero no olvide cambiar status a <b>Activo</b> desde el módulo de productos simples y ajustar las existencias físicas del inventario.";
            } else {
                $stock_product = StockProduct::where('product_id', $product->id)->first();
                if($stock_product) {
                    if($stock_product->quantity < $quantity) {
                        $this->low_stock_messages [] = 
                        "Hay $stock_product->quantity {$stock_product->product->unit->name} de <b>{$stock_product->product->name}</b>, se requieren $quantity. Reponer para cumplir con la orden de venta a tiempo";
                    }
                } else {
                    $this->no_stock_record_messages [] = 
                        "Se creará automáticamente el registro de <b>$product->name</b> en inventario ya que no existe actualmente. Pero recuerda ajustar las existencias físicas del inventario";
                }
            }
        }
    }
    
    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }
    
    public function openModal(Company $company, $emit_response_to)
    {
        $this->open = true;
        $this->emit_response_to = $emit_response_to;
        $this->company = $company;
    }
    
    public function store()
    {
        $this->validate();

        $sell_ordered_product = new SellOrderedProduct([
            'quantity' => $this->quantity,
            'for_sell' => $this->for_sell,
            'new_design' => $this->new_design,
            'notes' => $this->notes,
            'company_has_product_for_sell_id' => $this->product_for_sell,
        ]);

        $this->emitTo($this->emit_response_to,'selected-sell-ordered-product', $sell_ordered_product->toArray());
        
        $this->reset();
    }

    public function render()
    {
        return view('livewire.sell-ordered-product.create-sell-ordered-product');
    }
}
