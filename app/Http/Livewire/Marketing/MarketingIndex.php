<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MarketingProject;
use Livewire\Component;
use Livewire\WithPagination;

class MarketingIndex extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'project_owner_id' => 'creador de proyecto',
        'created_at' => 'creado el',
        'project_name' => 'nombre de proyecto',
        'project_cost' => 'costo aproximado',
        'authorized_by_id' => 'Autorización',
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

    public function show(MarketingProject $project)
    {
        //$this->emitTo('design-department.show-design-department', 'openModal', $design_order);
    }

    // public function edit(MarketingProject $project)
    // {
    //     $this->emitTo('design-department.edit-design-department', 'openModal', $design_order);
    // }

    public function render()
    {
        $marketing_projects = MarketingProject::where(function ($q) {
            $q->where('id', 'like', "%$this->search%")
                ->orWhere('project_name', 'like', "%$this->search%")
                ->orWhereHas('owner', function ($query) {
                    $query->where('name', 'like', "%$this->search%");
                });
        })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        return view('livewire.marketing.marketing-index', compact('marketing_projects'));
    }
}
