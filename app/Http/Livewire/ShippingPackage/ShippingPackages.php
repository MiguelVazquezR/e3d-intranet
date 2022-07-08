<?php

namespace App\Http\Livewire\ShippingPackage;

use App\Mail\SellOrderShippedMailable;
use App\Models\SellOrder;
use App\Models\SellOrderedProduct;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ShippingPackages extends Component
{
    public
        $open = false,
        $loading = false,
        $sell_order,
        $products_for_package = [],
        $packaged_products = [],
        $notify_contact = 1;

    protected $listeners = [
        'openModal',
        'loadPackages',
        'partialShipment',
        'shipment',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'sell_order',
            ]);
        }
    }

    public function mount()
    {
        $this->sell_order = new SellOrder();
    }

    public function openModal(SellOrder $sell_order)
    {
        $this->open = true;
        $this->sell_order = $sell_order;
        $this->loadPackages();
    }

    public function editPackages(SellOrderedProduct $sell_ordered_product)
    {
        $this->emitTo('shipping-package.edit-shipping-package', 'openModal', $sell_ordered_product);
    }

    public function createPackages(SellOrderedProduct $sell_ordered_product)
    {
        $this->emitTo('shipping-package.create-shipping-package', 'openModal', $sell_ordered_product);
    }

    public function partialShipment()
    {
        $this->emitTo('shipping-package.partial-shipment', 'openModal', $this->sell_order, $this->notify_contact);
    }

    public function alert()
    {
        $this->emit('confirm', ['shipping-package.shipping-packages', 'shipment', '', "Se notificará a gerencia de este movimiento"]);
        $this->loading = true;
    }

    public function shipment()
    {
        foreach ($this->sell_order->SellOrderedProducts as $s_o_p) {
            foreach ($s_o_p->shippingPackages as $package) {
                $package->status = "Enviado";
                $package->save();
            }
        }

        $this->sell_order->status = "Totalmente enviado";
        $this->sell_order->save();

        // send email notification
        if ($this->notify_contact) {
            Mail::to($this->sell_order->contact->email)
                ->bcc('maribel@emblemas3d.com')
                ->queue(new SellOrderShippedMailable($this->sell_order));
        } else {
             Mail::to('maribel@emblemas3d.com')
                 ->queue(new SellOrderShippedMailable($this->sell_order));
        }

        $this->resetExcept('sell_order');
        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emit('success', 'Paquetes enviados');
    }

    public function loadPackages()
    {
        $this->products_for_package =
            SellOrderedProduct::where('sell_order_id', $this->sell_order->id)
            ->where('status', 'Terminado')
            ->get();
        $this->packaged_products =
            SellOrderedProduct::where('sell_order_id', $this->sell_order->id)
            ->where('status', 'Empacado')
            ->get();
    }

    public function render()
    {
        // $this->loadProducts();
        return view('livewire.shipping-package.shipping-packages', [
            'products_for_package' => $this->products_for_package,
            'packaged_products' => $this->packaged_products,
        ]);
    }
}
