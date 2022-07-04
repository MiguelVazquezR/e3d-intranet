<?php

namespace App\Http\Livewire\ShippingPackage;

use App\Models\SellOrderedProduct;
use App\Models\ShippingPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class EditShippingPackage extends Component
{
    use WithFileUploads;

    public
        $open = false,
        $large,
        $width,
        $height,
        $weight,
        $quantity,
        $inside_image,
        $inside_image_id,
        $outside_image,
        $outside_image_id,
        $edit_index,
        $packages_list = [],
        $images_list = [],
        $temporary_deleted_list = [],
        $deleted_message = "Paquete eliminado",
        $sell_ordered_product;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $rules = [
        'packages_list' => 'required',
    ];

    protected $package_rules = [
        'large' => 'required|numeric',
        'width' => 'required|numeric',
        'height' => 'required|numeric',
        'weight' => 'required|numeric',
        'inside_image' => 'required|image',
        'outside_image' => 'required|image',
    ];

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->inside_image_id = rand();
        $this->outside_image_id = rand();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'sell_ordered_product',
                'shipping_package',
            ]);
            $this->inside_image_id = rand();
            $this->outside_image_id = rand();
        }
    }

    public function openModal(SellOrderedProduct $sell_ordered_product)
    {
        $this->open = true;
        $this->sell_ordered_product = $sell_ordered_product;
        foreach ($sell_ordered_product->shippingPackages as $package) {
            $this->packages_list[] = $package->toArray();
            $this->images_list[] = ['inside', 'outside'];
        }
    }

    public function addItemToList()
    {
        $this->package_rules["quantity"] = "required|numeric|max:{$this->sell_ordered_product->quantity}";
        $validated_data = $this->validate($this->package_rules);

        $inside_image_name = time() . Str::random(10) . '.' . $this->inside_image->extension();
        $validated_data["inside_image"] = "public/shipping-packages/$inside_image_name";
        $outside_image_name = time() . Str::random(10) . '.' . $this->outside_image->extension();
        $validated_data["outside_image"] = "public/shipping-packages/$outside_image_name";

        $this->images_list[] = [
            "inside_image" => $this->inside_image,
            "outside_image" => $this->outside_image,
        ];

        $package = new ShippingPackage(
            [
                'sell_ordered_product_id' => $this->sell_ordered_product->id,
                'user_id' => Auth::user()->id
            ]
                + $validated_data
        );

        $this->packages_list[] = $package->toArray();

        $this->resetItem();
    }

    public function resetItem()
    {
        $this->reset([
            'large',
            'width',
            'height',
            'weight',
            'quantity',
            'edit_index',
            'inside_image',
            'outside_image',
        ]);
        $this->inside_image_id = rand();
        $this->outside_image_id = rand();
    }

    public function deleteItem($index)
    {
        if (array_key_exists('id', $this->packages_list[$index])) {
            $this->temporary_deleted_list[] = $this->packages_list[$index]["id"];
        } else {
            unset($this->packages_list[$index]);
            unset($this->images_list[$index]);
        }
    }

    public function removeFromTemporaryDeletedList($id)
    {
        $index = array_search($id, $this->temporary_deleted_list);
        unset($this->temporary_deleted_list[$index]);
    }

    public function update()
    {
        $this->validate(
            null,
            ['packages_list.required' => 'Agregue por lo menos un paquete']
        );

        // create all packages
        foreach ($this->packages_list as $i => $package) {
            if (!array_key_exists('id', $package)) {
                ShippingPackage::create($package);
                //storage optimized images
                $image_path = storage_path() . "/app/{$package['inside_image']}";
                Image::make($this->images_list[$i]["inside_image"])
                    ->save($image_path, 40);
                $image_path = storage_path() . "/app/{$package['outside_image']}";
                Image::make($this->images_list[$i]["outside_image"])
                    ->save($image_path, 40);
            }
        }
        
        if (!empty($this->temporary_deleted_list)) {
            // delete old package on temporary list
            $delete_packages = ShippingPackage::whereIn('id', $this->temporary_deleted_list);
            foreach($delete_packages->get() as $delete) {
                Storage::delete([$delete->inside_image, $delete->outside_image]);
            }
            $delete_packages->delete();
        }
        
        // set statuses
        if (!SellOrderedProduct::find($this->sell_ordered_product->id)->shippingPackages->count()) {
            $this->sell_ordered_product->status = 'Terminado';
            $this->sell_ordered_product->save();

            $this->sell_ordered_product->sellOrder->status = 'Paquete(s) removido(s)';
            $this->sell_ordered_product->sellOrder->save();
        }

        $this->resetExcept([
            'sell_ordered_product'
        ]);

        $this->emitTo('shipping-package.shipping-packages', 'loadPackages');
        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emit('success', 'Paquetes actualizados');
    }

    public function render()
    {
        return view('livewire.shipping-package.edit-shipping-package');
    }
}
