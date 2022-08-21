<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Mail\PurchaseOrderMailable;
use App\Mail\PurchaseOrderReceivedMailable;
use App\Models\MovementHistory;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\StockProduct;
use App\Notifications\FinishedOrderNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrders extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'user_id' => 'solicitante',
        'supplier_id' => 'proveedor',
        'created_at' => 'creada el',
        'status' => 'status',
    ];

    protected $listeners = [
        'render',
        'delete',
        'show',
        'edit',
        'emitOrder',
        'receiveOrder',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingElements()
    {
        $this->resetPage();
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function show(PurchaseOrder $purchase_order)
    {
        $this->emitTo('purchase-order.show-purchase-order', 'openModal', $purchase_order);
    }

    public function edit(PurchaseOrder $purchase_order)
    {
        $this->emitTo('purchase-order.edit-purchase-order', 'openModal', $purchase_order);
    }

    public function delete(PurchaseOrder $purchase_order)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó orden de compra con ID: {$purchase_order->id}"
        ]);

        $purchase_order->delete();

        $this->emit('success', 'Orden de compra eliminada');
    }

    public function openUnitsConvertionModal(PurchaseOrder $purchase_order)
    {
        $this->emitTo('purchase-order.units-convertion', 'openModal', $purchase_order);
    }

    public function receiveOrder(PurchaseOrder $purchase_order, $convertions)
    {
        $success_message = "";
        $purchase_order->received_at = date('Y-m-d');
        $purchase_order->status = 'Recibido';
        $purchase_order->save();

        // notify to request's creator
        $this->design_order->creator->notify(new FinishedOrderNotification('compra', $this->purchase_order->id, 'Se ha recibido la mercancía','purchase-orders'));

        // create stock movements
        $products = $purchase_order->purchaseOrderedProducts;
        foreach ($products as $i => $pop) {
            $stock_product = StockProduct::where('product_id', $pop->product->id)->first();
            $_quantity = $pop->quantity * $convertions[$i];
            if (!$stock_product) {
                $stock_product = StockProduct::create([
                    'product_id'  =>  $pop->product->id,
                    'location'  =>  'Por definir',
                    'quantity'  =>  $_quantity,
                    'image'  => 'public/stock_products/default.jpg',
                ]);
                // create movement history
                MovementHistory::create([
                    'movement_type' => 1,
                    'user_id' => Auth::user()->id,
                    'description' => "Se agregó producto '{$pop->product->name}' a inventario mediante la recepción de una orden de compra."
                ]);
                $success_message .= "<li class='list-disc ml-2 text-sm text-green-500'>Se agregó <b>{$pop->product->name}</b> a inventario con {$_quantity} unidades</li>";
            } else {
                $stock_product->quantity = $stock_product->quantity + $_quantity;
                $stock_product->save();
                $success_message .= "<li class='list-disc ml-2 text-sm text-green-500'>Se agregaron {$_quantity} unidades al inventario <b>({$pop->product->name})</b></li>";
                StockMovement::create([
                    'quantity' => $_quantity,
                    'notes' => "Agregado desde OC con ID {$purchase_order->id}",
                    'user_id' => Auth::user()->id,
                    'stock_product_id' => $stock_product->id,
                    'stock_action_type_id' => 9, //compra
                ]);
            }

            // send email notification
            Mail::to('maribel@emblemas3d.com')
                // ->bcc('miguelvz26.mv@gmail.com')
                ->send(new PurchaseOrderReceivedMailable($purchase_order));
        }

        $this->emit('success', 'Orden de compra recibida.' . $success_message);
        $this->render();
    }

    public function emitOrder(PurchaseOrder $purchase_order, $notify_vendor)
    {
        $purchase_order->emitted_at = date('Y-m-d');
        $purchase_order->status = 'En espera de recepción';
        $purchase_order->save();

        // send email notification
        if ($notify_vendor) {
            Mail::to($purchase_order->contact->email)
                ->bcc('maribel@emblemas3d.com')
                ->send(new PurchaseOrderMailable($purchase_order));
            $this->emit('success', 'Orden de compra emitida a proveedor');
        } else {
            Mail::to('maribel@emblemas3d.com')
                ->send(new PurchaseOrderMailable($purchase_order));
            $this->emit('success', 'Orden de compra marcada como emitida. No olvides notificar al proveedor');
        }

        $this->render();
    }

    public function cancelOrder(PurchaseOrder $purchase_order)
    {
        $purchase_order->status = 'Cancelada';
        $purchase_order->save();

        $this->emit('success', 'Orden de compra cancelada. Avisar a proveedor');
        $this->render();
    }

    public function alert(PurchaseOrder $purchase_order)
    {
        $this->emit('two-options', [
            "Al emitir esta OC se enviará un correo a gerencia. <br>
            También es posible mandar la OC al contacto relacionado (<b>{$purchase_order->contact->email}</b>) con una plantilla
            ya definida o puede marcar esta OC como emitida y enviar correo al proveedor con su propia plantilla",
            'Enviar a proveedor',
            'Sólo marcar como emitida',
            'purchase-order.purchase-orders',
            'emitOrder',
            $purchase_order
        ]);
        $this->loading = true;
    }

    public function render()
    {
        $purchase_orders = PurchaseOrder::where('id', 'like', "%$this->search%")
            ->orWhereHas('supplier', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orWhereHas('creator', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        return view('livewire.purchase-order.purchase-orders', [
            'purchase_orders' => $purchase_orders,
        ]);
    }
}
