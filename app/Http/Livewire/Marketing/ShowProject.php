<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MarketingProject;
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
