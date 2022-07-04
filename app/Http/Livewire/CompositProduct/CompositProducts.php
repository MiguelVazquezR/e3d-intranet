<?php

namespace App\Http\Livewire\CompositProduct;

use App\Models\CompositProduct;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class CompositProducts extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $filter_family;

    public $table_columns = [
        'id' => 'id',
        'alias' => 'alias',
        'created_at' => 'compuesto por',
        'product_status_id' => 'estado',
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

    public function edit(CompositProduct $product)
    {
        $this->emitTo('composit-product.edit-composit-product', 'openModal', $product);
    }

    public function show(CompositProduct $product)
    {
        $this->emitTo('composit-product.show-composit-product', 'openModal', $product);
    }

    public function delete(CompositProduct $product)
    {
        Storage::delete([$product->image]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó producto compuesto de nombre: {$product->alias}"
        ]);

        $product->delete();

        $this->emit('success', 'Producto compuesto eliminado.');
    }

    public function render()
    {

        $products = CompositProduct::where('alias', 'like', "%$this->search%")
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);


        return view('livewire.composit-product.composit-products', [
            'composit_products' => $products,
        ]);
    }

}
