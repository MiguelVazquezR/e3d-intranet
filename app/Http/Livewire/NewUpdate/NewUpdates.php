<?php

namespace App\Http\Livewire\NewUpdate;

use App\Models\NewUpdate;
use Livewire\Component;

class NewUpdates extends Component
{
    public $updates;

    protected $listeners = [
        'render',
    ];

    public function mount()
    {
        $this->updates = NewUpdate::all();
    }

    public function edit(NewUpdate $update)
    {
        $this->emitTo('new-update.edit-new-update', 'openModal', $update);
    }

    public function delete(NewUpdate $update)
    {
        $update->delete();

        $this->emit('success', 'Actualización eliminada.');
    }

    public function render()
    {
        $this->updates = NewUpdate::all();
        return view('livewire.new-update.new-updates');
    }
}
