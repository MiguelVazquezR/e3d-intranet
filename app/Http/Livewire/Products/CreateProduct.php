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
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use WithFileUploads;

    public $open = false,
        $name,
        $product_family_id,
        $product_material_id,
        $product_status_id,
        $measurement_unit_id,
        $min_stock,
        $image,
        $image_id,
        $materials = [];

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $rules = [
        'name' => 'required|max:191',
        'product_material_id' => 'required',
        'product_family_id' => 'required',
        'measurement_unit_id' => 'required',
        'product_status_id' => 'required',
        'min_stock' => 'required',
        'image' => 'required|image',
    ];

    protected $listeners = [
        'render',
        'updatedProductFamilyId'
    ];


    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept(['open']);
            $this->image_id = rand();
        }
    }
    
    public function updatedProductFamilyId()
    {
        $this->materials = ProductMaterial::where('product_family_id', $this->product_family_id)->get();
    }

    public function mount()
    {
        $this->image_id = rand();
    }

    public function render()
    {
        return view('livewire.products.create-product', [
            'families' => ProductFamily::all(),
            'materials' => $this->materials,
            'statuses' => ProductStatus::all(),
            'units' => MeasurementUnit::all(),
        ]);
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function store()
    {
        $validated_data = $this->validate();

        // edit image and save it on server
        $image_name = ImageHandler::prepareImage($this->image, "products");

        unset($validated_data["image"]);

        $product = Product::create( $validated_data + [
                'image'  =>  "public/products/$image_name"
            ]
        );

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó nuevo producto simple de nombre {$product->name}"
        ]);

        $this->image_id = rand();

        $this->reset();

        $this->emitTo('products.products', 'render');
        $this->emit('success', 'Nuevo producto agregado');
    }
}
