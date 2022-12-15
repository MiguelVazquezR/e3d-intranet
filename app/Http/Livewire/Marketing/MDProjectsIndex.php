<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MarketingProject;
use App\Models\MarketingResult;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class MDProjectsIndex extends Component
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
        'project_cost' => 'costo',
        'updated_at' => 'Progreso',
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
        $this->emitTo('marketing.show-project', 'openModal', $project);
    }

    // public function edit(MarketingProject $project)
    // {
    //     $this->emitTo('design-department.edit-design-department', 'openModal', $design_order);
    // }

    public function delete(MarketingProject $project)
    {
        // tasks
        $project->tasks->each(function ($task){
            $task->users->each(function ($item) use ($task){
                $result = MarketingResult::where('marketing_task_user_id', $item->pivot->id)->first();
                if($result) {
                    Storage::delete([$result->file]);
                    $result->delete();
                }
                $item->marketingTasks()->detach($task->id);
            });
            $task->delete();
        });

        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => auth()->user()->id,
            'description' => "Se eliminó proyecto de marketing de nombre {$project->project_name}"
        ]);

        $project->delete();

        $this->emit('success', 'Proyecto de marketing eliminado.');
    }

    public function render()
    {
        $marketing_projects = MarketingProject::where(function ($q) {
            $q->where('id', 'like', "%$this->search%")
                ->orWhere('project_name', 'like', "%$this->search%")
                ->orWhereHas('creator', function ($query) {
                    $query->where('name', 'like', "%$this->search%");
                });
        })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        return view('livewire.marketing.m-d-projects-index', compact('marketing_projects'));
    }
}
