<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MarketingProject;
use App\Models\MarketingResult;
use App\Models\User;
use App\ServiceClasses\ImageHandler;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowProject extends Component
{
    use WithFileUploads;

    public $open = false,
        $tasks = [],
        $evidence,
        $evidence_id,
        $project;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'project.feedback' => 'max:500'
    ];

    public function mount()
    {
        $this->evidence_id = rand();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
            $this->evidence_id = rand();
        }
    }

    public function sendFeedback()
    {
        $this->project->save();
        $this->emit('success', 'Se han guardado los comentarios');
    }

    public function completeTask($task_id, $user_id, $pivot_id)
    {
        $file_name = "";
        if (in_array($this->evidence->extension(), $this->image_extensions)) {
            //save as image
            $file_name = ImageHandler::prepareImage($this->evidence, "marketing-evidences");
            $path = "public/marketing-evidences/$file_name";
        } else {
            //save as other file
            $file_name = $this->evidence->store('marketing-evidences', 'public');
            $path = "public/$file_name";
        }
        MarketingResult::create([
            'marketing_task_user_id' => $pivot_id,
            'file' => $path,
        ]);

        // add timestamp of finished
        $user = User::find($user_id);
        $user->marketingTasks()->updateExistingPivot($task_id, ['finished_at' => now()->toDateTimeString()]);
        $this->refreshProject();
        $this->emitTo('marketing.marketing-index', 'render');
        $this->emit('success', 'Tarea marcada como completada');
    }

    public function refreshProject()
    {
        $this->project = MarketingProject::find($this->project->id);
        $this->tasks = $this->project->tasks;
    }

    public function authorize()
    {
        $this->project->authorized_by_id = auth()->user()->id;
        $this->project->authorized_at = now()->toDateTimeString();
        $this->project->save();
        $this->refreshProject();
        $this->emitTo('marketing.marketing-index', 'render');
        $this->emit('success', 'Proyecto autorizado');
    }

    public function cancel()
    {
        $this->project->authorized_by_id = null;
        $this->project->authorized_at = null;
        $this->project->save();
        $this->refreshProject();
        $this->emit('success', 'Proyecto cancelado');
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
