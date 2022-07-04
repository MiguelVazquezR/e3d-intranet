<?php

namespace App\Http\Livewire\StockCompositProduct;

use App\Models\StockCompositProduct;
use App\Models\StockCompositProductMovement;
use FontLib\TrueType\Collection;
use Livewire\Component;

class ShowStockCompositProduct extends Component
{
    public $open = false,
        $stock_product,
        $stock_movements = [],
        $human_format = true,
        $action = 2,
        $active_tab = 0;

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function mount()
    {
        $this->stock_product = new StockCompositProduct();
        // $this->stock_movements = new Collection();
        $this->stock_product->composit_product_id = 1;
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
            StockCompositProductMovement::where('stock_composit_product_id', $this->stock_product->id)
            ->WhereHas('action', function ($query) {
                $query->where('movement', $this->action);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openModal(StockCompositProduct $stock_product)
    {
        $this->stock_product = $stock_product;
        $this->stock_movements =
        StockCompositProductMovement::where('stock_composit_product_id', $stock_product->id)
            ->WhereHas('action', function ($query) {
                $query->where('movement', $this->action);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.stock-composit-product.show-stock-composit-product');
    }
}
