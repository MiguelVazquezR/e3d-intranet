<?php

namespace App\Http\Livewire\StockCompositProduct;

use App\Models\CompositProduct;
use App\Models\MovementHistory;
use App\Models\StockActionType;
use App\Models\StockCompositProduct;
use App\Models\StockCompositProductMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class EditStockCompositProduct extends Component
{
    use WithFileUploads;

    public $open = false,
        $image,
        $image_id,
        $stock_product,
        $selected_product,
        $stock_movements = [],
        $human_format = true,
        $action = 2,
        $active_tab = 0;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-composit-product' => 'selectedCompositProduct',
        'refresh-movements' => 'updatedAction',
    ];

    protected $rules = [
        'stock_product.location' => 'required|max:191',
        'stock_product.composit_product_id' => 'required',
        'stock_product.quantity' => 'required|min:1',
    ];

    public function mount()
    {
        $this->image_id = rand();
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
            $this->image_id = rand();
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

    public function selectedCompositProduct(CompositProduct $selection)
    {
        $this->selected_product = $selection;
    }

    public function openModal(StockCompositProduct $stock_product)
    {
        $this->stock_product = $stock_product;
        $this->open = true;
        $this->selected_product = CompositProduct::find($stock_product->composit_product_id);
    }

    public function update()
    {
        if ($this->image) {
            $this->rules['image'] = 'image';
        }
        $this->validate();

        if ($this->image) {
            Storage::delete([$this->stock_product->image]);
            //storage optimized image
            $image_name = time() . Str::random(10) . '.' . $this->image->extension();
            $image_path = storage_path() . "/app/public/stock_products/$image_name";
            Image::make($this->image)
                ->save($image_path, 40);
            $this->stock_product->image = "public/stock_products/$image_name";
        }

        $this->stock_product->composit_product_id  =  $this->selected_product->id;

        $this->stock_product->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó producto compuesto de inventario con ID: {$this->stock_product->id}"
        ]);

        $this->reset([
            'open',
            'image',
        ]);

        $this->image_id = rand();

        $this->emit('success', 'producto de inventario actualizado.');
    }

    public function render()
    {
        return view('livewire.stock-composit-product.edit-stock-composit-product', [
            'stock_action_types' => StockActionType::all(),
        ]);
    }
}
