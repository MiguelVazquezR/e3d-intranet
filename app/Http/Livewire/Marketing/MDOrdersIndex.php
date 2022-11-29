<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MarketingOrder;
use Livewire\Component;
use Livewire\WithPagination;

class MDOrdersIndex extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'user_id' => 'solicitante',
        'created_at' => 'creado el',
        'order_name' => 'nombre de la órden',
        'authorized_by_id' => 'Autorización',
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
        $this->emitTo('marketing.m-d-orders-show', 'openModal', $marketing_order);
    }

    // public function edit(MarketingOrder $marketing_order)
    // {
    //     $this->emitTo('marketing.m-d-orders-edit', 'openModal', $marketing_order);
    // }

    public function render()
    {
        if (auth()->user()->can('autorizar_ordenes_mercadotecnia')) {
            $marketing_orders = MarketingOrder::where('id', 'like', "%$this->search%")
                ->orWhere('order_name', 'like', "%$this->search%")
                ->orWhereHas('creator', function ($query) {
                    $query->where('name', 'like', "%$this->search%");
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->elements);
        } else {
            $marketing_orders = MarketingOrder::whereNotNull('authorized_user_id')
                ->where(function ($query) {
                    $query->where('id', 'like', "%$this->search%")
                        ->orWhere('order_name', 'like', "%$this->search%")
                        ->orWhereHas('creator', function ($query) {
                            $query->where('name', 'like', "%$this->search%");
                        });
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->elements);
        }

        return view('livewire.marketing.m-d-orders-index', compact('marketing_orders'));
    }
}
