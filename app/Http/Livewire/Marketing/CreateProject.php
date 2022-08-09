<?php

namespace App\Http\Livewire\Marketing;

use App\Mail\ApproveMailable;
use App\Models\MarketingProject;
use App\Models\MarketingTask;
use App\Models\MovementHistory;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateProject extends Component
{
    public $open = false,
        $project_name,
        $project_cost,
        $objective,
        $user_list = [],
        $tasks = [],
        $user_id;

    // Task attributes
    public $description,
        $estimated_finish;

    protected $listeners = [
        'render',
    ];

    protected $rules = [
        'project_name' => 'required',
        'project_cost' => 'required|numeric|min:0',
        'objective' => 'required',
        'tasks' => 'required',
    ];

    protected $task_rules = [
        'user_list' => 'required',
        'description' => 'required',
        'estimated_finish' => 'required',
    ];

    // task involved methods ------------------------------------
    public function updatedUserId($user_id)
    {
        if ($user_id === 'all') {
            $this->user_list = User::where('active', 1)->where('id', '!=', auth()->user()->id)->pluck('id')->all();
        } elseif (!in_array($user_id, $this->user_list)) {
            $this->user_list[] = $user_id;
        }
    }

    public function removeUser($index)
    {
        unset($this->user_list[$index]);
    }

    public function addTask()
    {
        $task = $this->validate($this->task_rules, [
            'user_list.required' => 'Agrega al menos un usuario para la tarea'
        ]);
        $this->tasks[] = $task;

        $this->reset([
            'description',
            'estimated_finish',
            'user_list',
            'user_id',
        ]);
    }
    // -----------------------------------------------------------------------

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }


    public function openModal()
    {
        $this->open = true;
    }

    public function store()
    {
        $this->validate(null, [
            'tasks.required' => 'Agregue por lo menos 1 tarea para este proyecto'
        ]);

        $project = MarketingProject::create([
            'project_name' => $this->project_name,
            'project_cost' => $this->project_cost,
            'objective' => $this->objective,
            'project_owner_id' => auth()->user()->id,
        ]);

        foreach ($this->tasks as $task) {
            $object_task = MarketingTask::Create([
                'description' => $task['description'],
                'estimated_finish' => $task['estimated_finish'],
                'marketing_project_id' => $project->id,
            ]);

            $object_task->users()->attach($task['user_list']);
        }

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => auth()->user()->id,
            'description' => "Se agregó nuevo proyecto al departamento de marketing con nombre: {$project->name}"
        ]);

        // send email notification
        if (App::environment('production'))
            Mail::to('maribel@emblemas3d.com')
                ->bcc('miguelvz26.mv@gmail.com')
                ->queue(new ApproveMailable('Projecto de marketing', $project->id, MarketingProject::class));

        $this->reset();

        $this->emitTo('marketing.marketing-index', 'render');
        $this->emit('success', 'Nuevo proyecto agregado y enviado a revisión, espere respuesta');
    }

    public function render()
    {
        $users = User::where('active', 1)->where('id', '!=', auth()->user()->id)->get();

        return view('livewire.marketing.create-project', compact('users'));
    }
}
