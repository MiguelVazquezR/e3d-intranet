<?php

namespace App\Http\Livewire\PayRoll;

use App\Models\MovementHistory;
use App\Models\PayRoll;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PayRolls extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'week' => 'semana',
        'start_period' => 'inicio',
        'end_period' => 'fin',
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

    public function show(PayRoll $pay_roll)
    {
        $this->emitTo('pay-roll.show-pay-roll', 'openModal', $pay_roll);
    }

    public function edit(PayRoll $pay_roll)
    {
        $this->emitTo('pay-roll-register.edit-pay-roll-register', 'openModal', $pay_roll);
    }

    public function delete(PayRoll $pay_roll)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó nómina de la semana: {$pay_roll->week}"
        ]);

        $pay_roll->delete();

        $this->emit('success', 'Nómina eliminada');
    }

    public function render()
    {
        $pay_rolls = PayRoll::where('closed', 1)
            ->where(function($query) {
                $query->orWhere('id', 'like', "%$this->search%")
                ->orWhere('week', 'like', "%$this->search%")
                ->orWhere('start_period', 'like', "%$this->search%")
                ->orWhere('end_period', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.pay-roll.pay-rolls', compact('pay_rolls'));
    }
}
