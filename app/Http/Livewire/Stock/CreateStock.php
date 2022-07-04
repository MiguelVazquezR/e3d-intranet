<?php

namespace App\Http\Livewire\Stock;

use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\StockProduct;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CreateStock extends Component
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
        'selected-product' => 'selectedProduct',
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
        $this->selected_product = new Product();
        $this->image_id = rand();
    }

    public function render()
    {
        return view('livewire.stock.create-stock');
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function selectedProduct(Product $selection)
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

        $stock_product = StockProduct::create([
            'product_id'  =>  $this->selected_product->id,
            'location'  =>  $this->location,
            'quantity'  =>  $this->quantity,
            'image'  =>  "public/stock_products/$image_name",
        ]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó producto simple a inventario de nombre '{$this->selected_product->name}'"
        ]);

        $this->image_id = rand();

        $this->reset();

        $this->emitTo('stock.stocks', 'render');
        $this->emit('success', 'Nuevo producto simple agregado al inventario');
    }
}
