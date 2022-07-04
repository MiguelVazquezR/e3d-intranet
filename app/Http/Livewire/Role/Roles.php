<?php

namespace App\Http\Livewire\Role;

use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'name' => 'nombre',
        'guard_name' => 'permisos',
        'created_at' => 'Creado el',
    ];

    protected $listeners = [
        'render',
        'delete',
        'show',
        'edit',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingElements()
    {
        $this->resetPage();
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function show(Role $role)
    {
        $this->emitTo('role.show-role', 'openModal', $role);
    }

    public function edit(Role $role)
    {
        $this->emitTo('role.edit-role', 'openModal', $role);
    }

    public function delete(Role $role)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó rol de nombre: {$role->name}"
        ]);

        $role->delete();

        $this->emit('success', 'Rol eliminado');
    }

    public function render()
    {
        $roles = Role::where('id', 'like', "%$this->search%")
            ->orWhere('name', 'like', "%$this->search%")
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.role.roles', [
            'roles' => $roles,
        ]);
    }

}
