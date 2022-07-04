<?php

namespace App\Http\Livewire\Bonus;

use App\Models\Bonus;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Bonuses extends Component
{
    use WithPagination; 

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'name' => 'nombre',
        'full_time' => 'tiempo completo',
        'half_time' => 'medio turno',
    ];

    protected $listeners = [
        'render',
        // 'delete',
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

    public function edit(Bonus $bonus)
    {
        $this->emitTo('bonus.edit-bonus', 'openModal', $bonus);
    }

    // public function delete(Bonus $bonus)
    // {
    //     // create movement history
    //     MovementHistory::create([
    //         'movement_type' => 3,
    //         'user_id' => Auth::user()->id,
    //         'description' => "Se eliminó día feriado de nombre: {$bonus->name}"
    //     ]);

    //     $bonus->delete();

    //     $this->emit('success', 'Día festivo eliminado');
    // }

    public function render()
    {
        $bonuses = Bonus::where('id', 'like', "%$this->search%")
            ->orWhere('name', 'like', "%$this->search%")
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.bonus.bonuses', [
            'bonuses' => $bonuses,
        ]);
    }

}
