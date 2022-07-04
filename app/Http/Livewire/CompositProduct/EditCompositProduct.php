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
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCompositProduct extends Component
{
    use WithFileUploads;

    public
        // $company,
        $composit_product,
        $open = false,
        $image,
        $image_id,
        $quantity,
        $notes,
        $edit_index = null,
        $selected_product,
        $products_list = [],
        $temporary_deleted_list = [];

    public $image_extensions = [
        'png',
        'jpg',
        'jpeg',
        'bmp'
    ];

    protected $rules = [
        'composit_product.alias' => 'required',
        'composit_product.product_status_id' => 'required',
        'composit_product.product_family_id' => 'required',
        'products_list' => 'required|array|min:2',
    ];

    protected $composit_product_datail_rules = [
        'quantity' => 'required',
        'notes' => 'max:191',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'delete',
        'selected-product' => 'selectedProduct',
    ];

    public function mount()
    {
        $this->image_id = rand();
        $this->composit_product = new CompositProduct();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'composit_product',
            ]);
            $this->image_id = rand();
        }
    }

    public function openModal(CompositProduct $composit_product)
    {
        $this->composit_product = $composit_product;
        $this->open = true;

        // load composit products detail list
        foreach ($this->composit_product->compositProductDetails as $c_p_d) {
            $this->products_list[] = $c_p_d->toArray();
        }
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

    public function addToTemporaryDeletedList($id)
    {
        $this->temporary_deleted_list[] = $id;
    }

    public function removeFromTemporaryDeletedList($id)
    {
        $index = array_search($id, $this->temporary_deleted_list);
        unset($this->temporary_deleted_list[$index]);
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
        if (array_key_exists('id', $this->products_list[$index])) {
            $this->addToTemporaryDeletedList($this->products_list[$index]["id"]);
        } else {
            unset($this->products_list[$index]);
        }
    }

    public function update()
    {
        if ($this->image) {
            $this->rules['image'] = 'image';
        }

        $this->validate(null, [
            'products_list.required' => 'Debe de haber mínimo 2 productos para formar el producto final'
        ]);

        if ($this->image) {
            Storage::delete([$this->composit_product->image]);

            // edit image and save it on server
            $image_name = ImageHandler::prepareImage($this->image, "composit-products");

            $this->composit_product->image = "public/composit-products/$image_name";
        }

        $this->composit_product->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó producto compuesto de nombre: {$this->composit_product->alias}"
        ]);

        $this->image_id = rand();

        // create new composit product datails and update olds
        foreach ($this->products_list as $c_p_d) {
            if (array_key_exists('id', $c_p_d)) {
                CompositProductDetails::find($c_p_d["id"])
                    ->update($c_p_d);
            } else {
                $c_p_d["composit_product_id"] = $this->composit_product->id;
                CompositProductDetails::create($c_p_d);
            }
        }

        // delete old products on temporary list
        CompositProductDetails::whereIn('id', $this->temporary_deleted_list)->delete();

        $this->image_id = rand();

        $this->resetExcept([
            'composit_product',
        ]);

        // $this->emitTo('customer.edit-customer', 'openModal', $this->composit_product->company);
        $this->reset();

        $this->emitTo('composit-product.composit-products', 'render');
        $this->emit('success', 'Producto compuesto actualizado');
    }

    public function render()
    {
        return view('livewire.composit-product.edit-composit-product', [
            'currencies' => Currency::all(),
            'statuses' => ProductStatus::all(),
            'families' => ProductFamily::all(),
        ]);
    }
}
