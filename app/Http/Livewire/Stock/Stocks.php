<?php

namespace App\Http\Livewire\Stock;

use App\Models\MovementHistory;
use App\Models\StockProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Stocks extends Component
{
    use WithPagination;

    public $search,
        $elements = 10;
        
    public $table_columns = [
        'id' => 'id',
        'product_id' => 'producto',
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

    public function render()
    {
        $stock_products = StockProduct::where("id", $this->search)
            ->orWhereHas('product', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        return view('livewire.stock.stocks', [
            'stock_products' => $stock_products,
        ]);
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

    public function edit(StockProduct $stock_product)
    {
        $this->emitTo('stock.edit-stock', 'openModal', $stock_product);
    }
    
    public function show(StockProduct $stock_product)
    {
        $this->emitTo('stock.show-stock', 'openModal', $stock_product);
    }

    public function delete(StockProduct $stock_product)
    {
        Storage::delete([$stock_product->image]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó producto simple '{$stock_product->product->name}' del inventario"
        ]);

        $stock_product->delete();

        $this->emit('success', 'Producto simple eliminado de inventario');
    }
}
