<?php

namespace App\Http\Livewire\NewUpdate;

use App\Models\NewUpdate;
use Livewire\Component;

class CreateNewUpdate extends Component
{
    public $open = false,
        $title,
        $description;

    protected $listeners = [
        'render',
    ];

    protected $rules = [
        'title' => 'required',
        'description' => 'required',
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
        $this->validate();

        NewUpdate::create([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->reset();

        $this->emitTo('new-update.new-updates', 'render');
        $this->emit('success', 'Nueva actualización agregada');
    }

    public function render()
    {
        return view('livewire.new-update.create-new-update');
    }
}
