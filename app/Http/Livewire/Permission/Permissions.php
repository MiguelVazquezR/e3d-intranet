<?php

namespace App\Http\Livewire\Permission;

use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'name' => 'nombre',
        'guard_name' => 'guard',
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

    public function show(Permission $permission)
    {
        $this->emitTo('permission.show-permission', 'openModal', $permission);
    }

    public function edit(Permission $permission)
    {
        $this->emitTo('permission.edit-permission', 'openModal', $permission);
    }

    public function delete(Permission $permission)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó permiso: {$permission->name}"
        ]);

        $permission->delete();

        $this->emit('success', 'Permiso eliminado');
    }

    public function render()
    {
        $permissions = Permission::where('id', 'like', "%$this->search%")
            ->orWhere('name', 'like', "%$this->search%")
            // ->orWhereHas('designer', function ($query) {
            //     $query->where('name', 'like', "%$this->search%");
            // })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.permission.permissions', [
            'permissions' => $permissions,
        ]);
    }

}
