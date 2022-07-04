<?php

namespace App\Http\Livewire\Products;

use App\Models\MeasurementUnit;
use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\ProductFamily;
use App\Models\ProductMaterial;
use App\Models\ProductStatus;
use App\ServiceClasses\ImageHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProduct extends Component
{
    use WithFileUploads;

    public $open = false,
        $product,
        $image,
        $image_id;

    protected $listeners = [
        'openModal',
        'render',
    ];

    public $image_extensions = [
        'png',
        'jpg',
        'jpeg',
        'bmp',
    ];

    protected $rules = [
        'product.name' => 'required|max:191',
        'product.product_material_id' => 'required',
        'product.product_family_id' => 'required',
        'product.product_status_id' => 'required',
        'product.measurement_unit_id' => 'required',
        'product.product_status_id' => 'required',
        'product.min_stock' => 'required|min:1',
    ];


    public function mount()
    {
        $this->image_id = rand();
        $this->product = new Product();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept(['open', 'product']);
            $this->image_id = rand();
        }
    }

    public function openModal(Product $product)
    {
        $this->product = $product;
        $this->open = true;
    }

    public function update()
    {
        if ($this->image) {
            $this->rules['image'] = 'image';
        }
        $this->validate();

        if ($this->image) {
            Storage::delete([$this->product->image]);

            // edit image and save it on server
            $image_name = ImageHandler::prepareImage($this->image, "products");

            $this->product->image = "public/products/$image_name";
        }

        $this->product->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó producto simple con ID {$this->product->id}"
        ]);

        $this->reset([
            'open',
            'image',
        ]);

        $this->image_id = rand();

        $this->emitTo('products.products', 'render');
        $this->emit('success', 'Producto actualizado.');
    }

    public function render()
    {
        return view('livewire.products.edit-product', [
            'families' => ProductFamily::all(),
            'materials' => ProductMaterial::all(),
            'statuses' => ProductStatus::all(),
            'units' => MeasurementUnit::all(),
        ]);
    }
}
