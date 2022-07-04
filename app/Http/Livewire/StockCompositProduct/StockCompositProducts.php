<?php

namespace App\Http\Livewire\StockCompositProduct;

use App\Models\MovementHistory;
use App\Models\StockCompositProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class StockCompositProducts extends Component
{
    use WithPagination;

    public $search,
        $elements = 10;
        
    public $table_columns = [
        'id' => 'id',
        'composit_product_id' => 'producto compuesto',
        'quantity' => 'cantidad',
        'location' => 'ubicación',
    ];

    public $sort = 'id',
        $direction = 'desc';

    protected $listeners = [
        'render',
        'delete',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingElements()
    {
        $this->resetPage();
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function edit(StockCompositProduct $stock_product)
    {
        $this->emitTo('stock-composit-product.edit-stock-composit-product', 'openModal', $stock_product);
    }
    
    public function show(StockCompositProduct $stock_product)
    {
        $this->emitTo('stock-composit-product.show-stock-composit-product', 'openModal', $stock_product);
    }

    public function delete(StockCompositProduct $stock_product)
    {
        Storage::delete([$stock_product->image]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó producto compuesto '{$stock_product->compositProduct->alias}' del inventario"
        ]);

        $stock_product->delete();

        $this->emit('success', 'Producto compuesto eliminado de inventario.');
    }

    public function render()
    {
        $stock_products = StockCompositProduct::where("id", $this->search)
            ->orWhereHas('compositProduct', function ($query) {
                $query->where('alias', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        return view('livewire.stock-composit-product.stock-composit-products', [
            'stock_products' => $stock_products,
        ]);
    }
}
