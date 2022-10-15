<?php

namespace App\Http\Livewire\MediaLibrary;

use Livewire\Component;
use Livewire\WithFileUploads;

class Upload extends Component
{
    use WithFileUploads;

    public $open = false,
        $files;
        
    protected $listeners = [
        'render',
    ];

    protected $rules = [
        'files' => 'array|min:1',
    ];

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
        // $this->validate(null, [
        //     'tasks.required' => 'Agregue por lo menos 1 tarea para este proyecto'
        // ]);
        // }

        // // create movement history
        // MovementHisto::create([
        //     'movement_type' => 1,
        //     'user_id' => auth()->user()->id,
        //     'description' => "Se agregó nuevo proyecto al departamento de marketing con nombre: {$project->name}"
        // ]);

        // $this->reset();

        // $this->emitTo('marketing.marketing-index', 'render');
        // $this->emit('success', "Nuevo proyecto agregado y enviado a revisi贸n a {$emails[0]} y {$emails[1]}, espere respuesta");
    }

    public function render()
    {
        return view('livewire.media-library.upload');
    }
}
