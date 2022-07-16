<?php

namespace App\Http\Livewire\PayRollMoreTime;

use App\Models\MovementHistory;
use App\Models\PayRollMoreTime;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search,
        $elements = 10;

    public $table_columns = [
        'id' => 'id',
        'user_id' => 'empleado',
        'created_at' => 'solicitado el',
        'par_roll_id' => 'Solicitud para nómina',
        'additional_time' => 'Tiempo solicitado',
        'authorized_by' => 'status',
    ];

    public $sort = 'id',
        $direction = 'desc';

    protected $listeners = [
        'render',
        'delete',
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

    public function show(PayRollMoreTime $additional_time)
    {
        $this->emitTo('pay-roll-more-time.show', 'openModal', $additional_time);
    }

    public function delete(PayRollMoreTime $additional_time)
    {
        Storage::delete([$additional_time->report]);

        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => auth()->user()->id,
            'description' => "Se eliminó solicitud de tiempo adicional del empleado {$additional_time->user->name}"
        ]);

        $additional_time->delete();

        $this->emit('success', 'Solicitud eliminada.');
    }

    public function render()
    {
        $additional_time_requests = PayRollMoreTime::where('id', 'like', "%$this->search%")
            ->orWhere('created_at', 'like', "%$this->search%")
            ->orWhereHas('user', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.pay-roll-more-time.index', compact('additional_time_requests'));
    }
}
