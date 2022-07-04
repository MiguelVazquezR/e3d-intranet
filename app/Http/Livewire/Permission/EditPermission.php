<?php

namespace App\Http\Livewire\Permission;

use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class EditPermission extends Component
{
    public $open = false,
        $permission;

    protected $rules = [
        'permission.name' => 'required',
    ];

    protected $listeners = [
        'openModal',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal(Permission $permission)
    {
        $this->open = true;
        $this->permission = $permission;
    }

    public function update()
    {
        $this->validate();

        $this->permission->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó permiso con id {$this->permission->id}"
        ]);

        $this->reset();

        $this->emitTo('permission.permissions', 'render');
        $this->emit('success', 'Permiso actualizado');
    }

    public function render()
    {
        return view('livewire.permission.edit-permission');
    }
}
