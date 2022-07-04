<?php

namespace App\Http\Livewire\Products;

use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\ProductFamily;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $filter_family;

    public $table_columns = [
        'id' => 'id',
        'name' => 'nombre',
        'product_material_id' => 'material',
        'product_family_id' => 'familia',
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

    public function render()
    {
        if ($this->filter_family) { //family filter
            $products = Product::where('name', 'like', "%$this->search%")
                ->Where('product_family_id', $this->filter_family)
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->elements);
        } else {
            $products = Product::where('name', 'like', "%$this->search%")
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->elements);
        }

        return view('livewire.products.products', [
            'products' => $products,
            'families' => ProductFamily::all(),
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

    public function edit(Product $product)
    {
        $this->emitTo('products.edit-product', 'openModal', $product);
    }

    public function show(Product $product)
    {
        $this->emitTo('products.show-product', 'openModal', $product);
    }

    public function delete(Product $product)
    {
        Storage::delete([$product->image]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó producto simple de nombre {$product->name}"
        ]);

        $product->delete();

        $this->emit('success', 'Producto eliminado.');
    }
}
