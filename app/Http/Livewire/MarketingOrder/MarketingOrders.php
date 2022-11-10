<?php

namespace App\Http\Livewire\MarketingOrder;

use App\Models\MarketingOrder;
use App\Models\MovementHistory;
use Livewire\Component;
use Livewire\WithPagination;

class MarketingOrders extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'user_id' => 'solicitante',
        'order_name' => 'Nombre de pedido',
        'created_at' => 'solicitado el',
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

    public function show(MarketingOrder $marketing_order)
    {
        $this->emitTo('marketing-order.show-marketing-order', 'openModal', $marketing_order);
    }

    public function edit(MarketingOrder $marketing_order)
    {
        $this->emitTo('marketing-order.edit-marketing-order', 'openModal', $marketing_order);
    }

    public function delete(MarketingOrder $marketing_order)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => auth()->user()->id,
            'description' => "Se eliminó orden de mercadotecnia con ID: {$marketing_order->id}"
        ]);

        $marketing_order->delete();

        $this->emit('success', 'Orden de mercadotecnia eliminada');
    }

    public function render()
    {
        $marketing_orders = MarketingOrder::where('id', 'like', "%$this->search%")
            ->orWhere('order_name', 'like', "%$this->search%")
            ->orWhereHas('creator', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.marketing-order.marketing-orders', [
            'marketing_orders' => $marketing_orders,
        ]);
    }
}
