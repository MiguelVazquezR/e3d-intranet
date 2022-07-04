<?php

namespace App\Http\Livewire\CompositProduct;

use App\Models\CompositProduct;
use App\Models\CompositProductDetails;
use App\Models\Currency;
use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\ProductFamily;
use App\Models\ProductStatus;
use App\ServiceClasses\ImageHandler;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCompositProduct extends Component
{
    use WithFileUploads;

    public $company,
        $open = false,
        $alias,
        $image,
        $image_id,
        $quantity,
        $notes,
        $product_family_id,
        $edit_index = null,
        $selected_product,
        $product_status_id,
        $products_list = [];

    public $image_extensions = [
        'png',
        'jpg',
        'jpeg',
        'bmp'
    ];

    protected $rules = [
        'alias' => 'required',
        'products_list' => 'required|array|min:1',
        'product_family_id' => 'required',
        'image' => 'required|image',
        'product_status_id' => 'required',
    ];

    protected $composit_product_datail_rules = [
        'quantity' => 'required',
        'notes' => 'max:191',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-product' => 'selectedProduct',
    ];

    public function mount()
    {
        $this->image_id = rand();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
            $this->image_id = rand();
        }
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function selectedProduct(Product $selection)
    {
        $this->selected_product = $selection;
    }

    public function _createElementInstance()
    {
        $validated_data = $this->validate($this->composit_product_datail_rules);

        return new CompositProductDetails(
            [
                'product_id' => $this->selected_product->id,
            ] + $validated_data
        );
    }

    public function addProductToList()
    {
        $composit_product_detail = $this->_createElementInstance();

        $this->products_list[] = $composit_product_detail->toArray();

        $this->resetProduct();
    }

    public function updateProductFromList()
    {
        $composit_product_detail = $this->_createElementInstance();

        $this->products_list[$this->edit_index] = $composit_product_detail->toArray();

        $this->resetProduct();
    }

    public function resetProduct()
    {
        $this->reset([
            'quantity',
            'notes',
            'selected_product',
            'edit_index',
        ]);
    }

    public function editItem($index)
    {
        $this->selected_product =
            Product::find($this->products_list[$index]["product_id"]);
        $this->quantity = $this->products_list[$index]["quantity"];
        $this->notes = $this->products_list[$index]["notes"];
        $this->edit_index = $index;
    }

    public function deleteItem($index)
    {
        unset($this->products_list[$index]);
    }

    public function store()
    {
        $validated_data = $this->validate(null, [
            'products_list.required' => 'Debe de haber mínimo un art[iculo relacionado para formar el producto final'
        ]);

        $composit_product = new CompositProduct($validated_data);

        // edit image and save it on server
        $image_name = ImageHandler::prepareImage($this->image, "composit-products");

        $composit_product->image = "public/composit-products/$image_name";

        $composit_product->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó nuevo producto compuesto de nombre: {$composit_product->alias}"
        ]);


        // create quoted products
        foreach ($this->products_list as $composit_product_detail) {
            $composit_product_detail["composit_product_id"] = $composit_product->id;
            CompositProductDetails::create($composit_product_detail);
        }

        $this->image_id = rand();

        $this->reset();

        $this->emitTo('composit-product.composit-products', 'render');
        $this->emit('success', 'Nuevo producto compuesto registrado');
    }

    public function render()
    {
        return view('livewire.composit-product.create-composit-product', [
            'currencies' => Currency::all(),
            'statuses' => ProductStatus::all(),
            'families' => ProductFamily::all(),
        ]);
    }
}
