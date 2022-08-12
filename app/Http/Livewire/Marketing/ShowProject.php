<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MarketingProject;
use App\Models\User;
use Livewire\Component;

class ShowProject extends Component
{
    public $open = false,
        $tasks = [],
        $project;

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'project.feedback' => 'max:500'
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function sendFeedback()
    {
        $this->project->save();
        $this->emit('success', 'Se han guardado los comentarios');
    }

    public function completeTask($task_id, $user_id)
    {
        $user = User::find($user_id);
        $user->marketingTasks()->sync([$task_id => ['finished_at' => now()->toDateTimeString()]]);
        $this->refreshTasks();
        $this->emit('success', 'Tarea marcada como completada');
    }

    public function refreshTasks()
    {
        $this->project = MarketingProject::find($this->project->id);
        $this->tasks = $this->project->tasks;
    }

    public function openModal(MarketingProject $project)
    {
        $this->open = true;
        $this->project = $project;
        $this->tasks = $project->tasks;
    }

    public function render()
    {
        return view('livewire.marketing.show-project');
    }
}
