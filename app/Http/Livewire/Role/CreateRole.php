<?php

namespace App\Http\Livewire\Role;

use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    public $open = false,
        $name,
        $permissions_selected = [];

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

        $role = Role::create($validated_data);

        $role->syncPermissions($this->permissions_selected);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se creó nuevo rol de nombre: {$role->name}"
        ]);

        $this->reset();

        $this->emitTo('role.roles', 'render');
        $this->emit('success', 'Nuevo rol registrado');
    }

    public function render()
    {
        $permissions = Permission::all()->pluck('name', 'id');
        return view('livewire.role.create-role', compact('permissions'));
    }
}
