<?php

namespace App\Http\Livewire\Stock;

use App\Models\StockMovement;
use App\Models\StockProduct;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ShowStock extends Component
{
    public $open = false,
        $stock_product,
        $stock_movements,
        $human_format = true,
        $action = 2,
        $active_tab = 0;

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function mount()
    {
        $this->stock_product = new StockProduct();
        $this->stock_movements = new Collection();
        $this->stock_product->product_id = 1;
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'stock_product',
                'stock_movements'
            ]);
        }
    }

    public function updatedAction()
    {
        $this->stock_movements =
            StockMovement::where('stock_product_id', $this->stock_product->id)
            ->WhereHas('action', function ($query) {
                $query->where('movement', $this->action);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openModal(StockProduct $stock_product)
    {
        $this->stock_product = $stock_product;
        $this->stock_movements =
            StockMovement::where('stock_product_id', $stock_product->id)
            ->WhereHas('action', function ($query) {
                $query->where('movement', $this->action);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.stock.show-stock');
    }
}
