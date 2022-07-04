<?php

namespace App\Http\Livewire\SellOrder;

use App\Models\MovementHistory;
use App\Models\SellOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SellOrders extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'user_id' => 'creador',
        'customer_id' => 'cliente',
        'priority' => 'prioridad',
        'created_at' => 'creada el',
        'updated_at' => 'operadores',
        'status' => 'status',
    ];

    protected $listeners = [
        'render',
        'delete',
        'show',
        'edit',
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

    public function show(SellOrder $sell_order)
    {
        $this->emitTo('sell-order.show-sell-order', 'openModal', $sell_order);
    }

    public function edit(SellOrder $sell_order)
    {
        $this->emitTo('sell-order.edit-sell-order', 'openModal', $sell_order);
    }
    
    public function shippingPackages(SellOrder $sell_order)
    {
        $this->emitTo('shipping-package.shipping-packages', 'openModal', $sell_order);
    }

    public function delete(SellOrder $sell_order)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó orden de venta con ID: {$sell_order->id}"
        ]);

        $sell_order->delete();

        $this->emit('success', 'Orden de venta eliminada');
    }

    public function render()
    {
        $sell_orders = SellOrder::where('id', 'like', "%$this->search%")
            ->orWhereHas('customer', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orWhereHas('creator', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        return view('livewire.sell-order.sell-orders', [
            'sell_orders' => $sell_orders,
        ]);
    }
}
