<?php

namespace App\Http\Livewire\Permission;

use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreatePermission extends Component
{
    public $open = false,
        $name;

    protected $rules = [
        'name' => 'required',
    ];

    protected $listeners = [
        'render',
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
        $validated_data = $this->validate();

        $permission = Permission::create($validated_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó un nuevo permiso: {$permission->name}"
        ]);

        $this->reset();

        $this->emitTo('permission.permissions', 'render');
        $this->emit('success', 'Nuevo permiso registrado');
    }

    public function render()
    {
        return view('livewire.permission.create-permission');
    }
}
