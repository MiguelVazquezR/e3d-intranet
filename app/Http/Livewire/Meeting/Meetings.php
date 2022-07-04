<?php

namespace App\Http\Livewire\Meeting;

use App\Models\Meeting;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Meetings extends Component
{
    use WithPagination;

    public $search,
        $elements = 10,
        $open_edit = false,
        $open_view = false,
        $sort = 'id',
        $direction = 'desc';

    public $table_columns = [
        'id' => 'id',
        'user_id' => 'creado por',
        'title' => 'título',
        'start' => 'inicia',
        'end' => 'termina',
        'status' => 'estado',
    ];

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

    public function show(Meeting $meeting)
    {
        $this->emitTo('meeting.show-meeting', 'openModal', $meeting);
    }

    public function edit(Meeting $meeting)
    {
        $this->emitTo('meeting.edit-meeting', 'openModal', $meeting);
    }

    public function delete(Meeting $meeting)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó reunión con ID {$meeting->id}"
        ]);

        $meeting->delete();

        $this->emit('success', 'Reunión eliminada.');
    }

    public function render()
    {
        $meetings = meeting::where('id', 'like', "%$this->search%")
            ->orWhere('title', 'like', "%$this->search%")
            ->orWhereHas('creator', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);

        // change meetings status
        foreach ($meetings as $meeting) {
            $meeting->changeStatus();
        }

        return view('livewire.meeting.meetings', [
            'meetings' => $meetings,
        ]);
    }
}
