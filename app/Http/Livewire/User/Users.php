<?php

namespace App\Http\Livewire\User;

use App\Models\MovementHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'name' => 'nombre',
        'email' => 'correo',
        'created_at' => 'feha ingreso',
        'active' => 'stauts',
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

    public function show(User $user)
    {
        $this->emitTo('user.show-user', 'openModal', $user);
    }

    public function edit(User $user)
    {
        $this->emitTo('user.edit-user', 'openModal', $user);
    }
    
    public function resetPassword(User $user)
    {
        $this->emitTo('user.reset-password', 'openModal', $user);
    }

    public function delete(User $user)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó usuario de nombre: {$user->name}"
        ]);

        $user->delete();
        
        $this->emit('success', 'Usuario eliminado');
    }

    public function render()
    {
        $users = User::where('id', 'like', "%$this->search%")
            ->orWhere('name', 'like', "%$this->search%")
            ->orWhere('email', 'like', "%$this->search%")
            // ->orWhereHas('designer', function ($query) {
            //     $query->where('name', 'like', "%$this->search%");
            // })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.user.users', [
            'users' => $users,
        ]);
    }
}
