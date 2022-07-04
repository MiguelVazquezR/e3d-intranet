<?php

namespace App\Http\Livewire\ProductionDepartment;

use App\Models\SellOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HomeProduction extends Component
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
        'show',
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
        $this->emitTo('production-department.show-production-department', 'openModal', $sell_order);
    }

    public function render()
    {
        if (Auth::user()->hasRole('Admin')) {
            $sell_orders = SellOrder::where('id', 'like', "%$this->search%")
                ->orWhereHas('customer', function ($query) {
                    $query->where('name', 'like', "%$this->search%");
                })
                ->orWhereHas('creator', function ($query) {
                    $query->where('name', 'like', "%$this->search%");
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->elements);
        } else {
            $sell_orders = SellOrder::whereHas('sellOrderedProducts', function ($query) {
                $query->whereHas('activityDetails', function ($query2) {
                    $query2->where('user_id', Auth::user()->id);
                });
            })
                ->where(function ($q) {
                    $q->where('id', 'like', "%$this->search%")
                        ->orWhereHas('customer', function ($query) {
                            $query->where('name', 'like', "%$this->search%");
                        })
                        ->orWhereHas('creator', function ($query) {
                            $query->where('name', 'like', "%$this->search%");
                        });
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->elements);
        }

        return view('livewire.production-department.home-production', compact('sell_orders'));
    }
}
