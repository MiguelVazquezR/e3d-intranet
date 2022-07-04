<?php

namespace App\Http\Livewire\DesignOrder;

use App\Models\DesignOrder;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DesignOrders extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'user_id' => 'solicitante',
        'design_name' => 'diseño',
        'designer_id' => 'diseñador(a)',
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

    public function show(DesignOrder $design_order)
    {
        $this->emitTo('design-order.show-design-order', 'openModal', $design_order);
    }

    public function edit(DesignOrder $design_order)
    {
        $this->emitTo('design-order.edit-design-order', 'openModal', $design_order);
    }

    public function delete(DesignOrder $design_order)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó orden de diseño con ID: {$design_order->id}"
        ]);

        $design_order->delete();

        $this->emit('success', 'Orden de venta eliminada');
    }

    public function render()
    {
        $design_orders = DesignOrder::where('id', 'like', "%$this->search%")
            ->orWhere('design_name', 'like', "%$this->search%")
            ->orWhereHas('creator', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orWhereHas('designer', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.design-order.design-orders', [
            'design_orders' => $design_orders,
        ]);
    }
}
