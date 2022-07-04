<?php

namespace App\Http\Livewire\Role;

use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRole extends Component
{
    public $open = false,
        $role,
        $permissions_selected = [];

    protected $rules = [
        'role.name' => 'required',
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

    public function openModal(Role $role)
    {
        $this->open = true;
        $this->role = $role;
        $this->permissions_selected = $role->permissions->pluck('id', 'name')->toArray();
    }

    public function update()
    {
        $this->validate();

        // delete all false permissions (unchecked) to prevent sql error
        foreach($this->permissions_selected as $i => $permission) {
            if (!$permission) {
                unset($this->permissions_selected[$i]);
            }
        }

        $this->role->save();
        $this->role->syncPermissions($this->permissions_selected);

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó rol con ID: {$this->role->id}"
        ]);

        $this->reset();

        $this->emitTo('role.roles', 'render');
        $this->emit('success', 'Rol actualizado');
    }


    public function render()
    {
        $permissions = Permission::all()->pluck('id', 'name');
        return view('livewire.role.edit-role', compact('permissions'));
    }
}
