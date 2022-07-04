<?php

namespace App\Http\Livewire\ShippingPackage;

use App\Models\SellOrderedProduct;
use App\Models\ShippingPackage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CreateShippingPackage extends Component
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
            ]);
            $this->inside_image_id = rand();
            $this->outside_image_id = rand();
        }
    }

    public function openModal(SellOrderedProduct $sell_ordered_product)
    {
        $this->open = true;
        $this->sell_ordered_product = $sell_ordered_product;
        $this->quantity = $this->sell_ordered_product->quantity;
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

    public function deleteItem($index)
    {
        unset($this->packages_list[$index]);
        unset($this->images_list[$index]);
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

    public function getStatus($finished, $all)
    {
        if ($finished == $all) {
            $status = 'Totalmente Empacado';
        } else {
            $status = "Parcialmente empacado ($finished de $all)";
        }
        return $status;
    }

    public function store()
    {
        $this->validate(
            null,
            ['packages_list.required' => 'Agregue por lo menos un paquete']
        );

        // create all packages
        foreach ($this->packages_list as $i => $package) {
            ShippingPackage::create($package);
            //storage optimized images
            $image_path = storage_path() . "/app/{$package['inside_image']}";
            Image::make($this->images_list[$i]["inside_image"])
                ->save($image_path, 40);
            $image_path = storage_path() . "/app/{$package['outside_image']}";
            Image::make($this->images_list[$i]["outside_image"])
                ->save($image_path, 40);
        }

        // set statuses
        $this->sell_ordered_product->status = 'Empacado';
        $this->sell_ordered_product->save();

        $all = SellOrderedProduct::where('sell_order_id', $this->sell_ordered_product->sell_order_id)
            ->get()
            ->count();
        $packaged = SellOrderedProduct::where('status', 'Empacado')
            ->where('sell_order_id', $this->sell_ordered_product->sell_order_id)
            ->get()
            ->count();

        $this->sell_ordered_product->sellOrder->status = $this->getStatus($packaged, $all);
        $this->sell_ordered_product->sellOrder->save();

        $this->resetExcept([
            'sell_ordered_product'
        ]);

        $this->emitTo('shipping-package.shipping-packages', 'loadPackages');
        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emit('success', 'Paquetes realizados');
    }

    public function render()
    {
        return view('livewire.shipping-package.create-shipping-package');
    }
}
