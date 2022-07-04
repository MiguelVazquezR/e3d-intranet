<?php

namespace App\Http\Livewire\StockCompositProduct;

use App\Models\CompositProduct;
use App\Models\MovementHistory;
use App\Models\StockCompositProduct;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CreateStockCompositProduct extends Component
{
    use WithFileUploads;

    public $open = false;
    public $image,
        $image_id,
        $selected_product,
        $location,
        $quantity;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $rules = [
        'selected_product' => 'required',
        'location' => 'required|max:191',
        'quantity' => 'required|min:1',
        'image' => 'required|image',
    ];

    protected $listeners = [
        'render',
        'selected-composit-product' => 'selectedCompositProduct',
    ];


    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept(['open']);
            $this->image_id = rand();
        }
    }

    public function mount()
    {
        $this->selected_product = new CompositProduct();
        $this->image_id = rand();
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function selectedCompositProduct(CompositProduct $selection)
    {
        $this->selected_product = $selection;
    }

    public function store()
    {
        $this->validate();

        
        //storage optimized image
        $image_name = time() . Str::random(10) . '.' . $this->image->extension();
        $image_path = storage_path() . "/app/public/stock_products/$image_name";
        Image::make($this->image)
            ->save($image_path, 40);

        $stock_product = StockCompositProduct::create([
            'composit_product_id'  =>  $this->selected_product->id,
            'location'  =>  $this->location,
            'quantity'  =>  $this->quantity,
            'image'  =>  "public/stock_products/$image_name",
        ]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó producto compuesto a inventario de nombre '{$this->selected_product->alias}'"
        ]);

        $this->image_id = rand();

        $this->reset();

        $this->emitTo('stock-composit-product.stock-composit-products', 'render');
        $this->emit('success', 'Nuevo producto compuesto agregado al inventario');
    }

    public function render()
    {
        return view('livewire.stock-composit-product.create-stock-composit-product');
    }
}
