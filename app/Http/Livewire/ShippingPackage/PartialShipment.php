<?php

namespace App\Http\Livewire\ShippingPackage;

use App\Mail\SellOrderShippedMailable;
use App\Models\SellOrder;
use App\Models\SellOrderedProduct;
use App\Models\ShippingPackage;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class PartialShipment extends Component
{
    public
        $open = false,
        $sell_order,
        $selected_packages = [],
        $packages_list = [],
        $notify_contact;

    protected $listeners = [
        'openModal',
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

    public function openModal(SellOrder $sell_order, $notify_contact)
    {
        $this->open = true;
        $this->notify_contact = $notify_contact;
        $this->sell_order = $sell_order;
        $this->loadPackages();
    }

    public function getStatus($finished, $all)
    {
        if ($finished == $all) {
            $status = 'Totalmente enviado';
        } else {
            $status = 'Parcialmente enviado';
        }
        return $status;
    }

    public function shipment()
    {
        foreach ($this->selected_packages as $package_id) {
            $package = ShippingPackage::find($package_id);
            $package->status = "Envio parcial";
            $package->save();
        }

        $packed = SellOrderedProduct::where('status', 'Empacado')
            ->where('sell_order_id', $this->sell_order->id)
            ->get()
            ->count();
        $all = SellOrderedProduct::where('sell_order_id', $this->sell_order->id)
            ->get()
            ->count();

        $this->sell_order->status = $this->getStatus($packed, $all);
        $this->sell_order->save();

        // send email notification
        if ($this->notify_contact) {
            Mail::to($this->sell_order->contact->email)
                ->bcc('maribel@emblemas3d.com')
                ->queue(new SellOrderShippedMailable($this->sell_order, ShippingPackage::whereIn('id', $this->selected_packages)->get()));
        } else {
            Mail::to('maribel@emblemas3d.com')
                ->queue(new SellOrderShippedMailable($this->sell_order, ShippingPackage::whereIn('id', $this->selected_packages)->get()));
        }

        $this->reset();
        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emitTo('shipping-package.shipping-packages', 'loadPackages');
        $this->emit('success', 'Paquetes enviados, se ha notificado al contacto de esta orden por correo');
    }

    public function loadPackages()
    {
        $this->packages_list = [];
        foreach ($this->sell_order->sellOrderedProducts as $sop) {
            foreach ($sop->shippingPackages as $package) {
                if ($package->status == 'Preparando para envío') {
                    $this->packages_list[] = $package->toArray();
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.shipping-package.partial-shipment');
    }
}
