<?php

namespace App\Http\Livewire\DesignDepartment;

use App\Models\DesignOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HomeDesign extends Component
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
        $this->emitTo('design-department.show-design-department', 'openModal', $design_order);
    }

    public function edit(DesignOrder $design_order)
    {
        $this->emitTo('design-department.edit-design-department', 'openModal', $design_order);
    }

    public function render()
    {
        if (Auth::user()->hasRole('Admin')) {
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
        } else {
            $design_orders = DesignOrder::where('designer_id', Auth::user()->id)
                ->whereNotNull('authorized_user_id')
                ->where(function ($q) {
                    $q->where('id', 'like', "%$this->search%")
                        ->orWhere('design_name', 'like', "%$this->search%")
                        ->orWhereHas('creator', function ($query) {
                            $query->where('name', 'like', "%$this->search%");
                        })
                        ->orWhereHas('designer', function ($query) {
                            $query->where('name', 'like', "%$this->search%");
                        });
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->elements);
        }

        return view('livewire.design-department.home-design', compact('design_orders'));
    }
}
