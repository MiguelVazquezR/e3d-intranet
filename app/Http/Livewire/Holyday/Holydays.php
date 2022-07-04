<?php

namespace App\Http\Livewire\Holyday;

use App\Models\Holyday;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Holydays extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'name' => 'nombre',
        'date' => 'fecha',
        'active' => 'Estado',
    ];

    protected $listeners = [
        'render',
        'delete',
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

    public function edit(Holyday $holyday)
    {
        $this->emitTo('holyday.edit-holyday', 'openModal', $holyday);
    }

    public function delete(Holyday $holyday)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó día feriado de nombre: {$holyday->name}"
        ]);

        $holyday->delete();

        $this->emit('success', 'Día festivo eliminado');
    }

    public function render()
    {
        $holydays = Holyday::where('id', 'like', "%$this->search%")
            ->orWhere('name', 'like', "%$this->search%")
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.holyday.holydays', [
            'holydays' => $holydays,
        ]);
    }

}
