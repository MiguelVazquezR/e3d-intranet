<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;

class CreateProject extends Component
{
    public $open = false,
        $project_name,
        $project_cost,
        $objective;

    protected $listeners = [
        'render',
    ];

    protected $rules = [
        'project_name' => 'required',
        'project_cost' => 'required|numeric|min:0',
        'objective' => 'required',
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
        // $this->validate();

        // $holyday = Holyday::create([
        //     'name' => $this->name,
        //     'date' => '2022-' . $this->month . '-' . $this->day,
        //     'active' => $this->active,
        // ]);

        // // create movement history
        // MovementHistory::create([
        //     'movement_type' => 1,
        //     'user_id' => Auth::user()->id,
        //     'description' => "Se agregó nuevo día feriado de nombre: {$holyday->name}"
        // ]);

        // $this->reset();

        // $this->emitTo('holyday.holydays', 'render');
        // $this->emit('success', 'Nuevo día festivo registrado');
    }

    public function render()
    {
        return view('livewire.marketing.create-project');
    }
}
