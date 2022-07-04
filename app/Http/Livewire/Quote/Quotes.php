<?php

namespace App\Http\Livewire\Quote;

use App\Models\MovementHistory;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Quotes extends Component
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
        'user_id' => 'creador',
        'customer_id' => 'cliente',
        'authorized_user_id' => 'autorizada por',
        'created_at' => 'fecha creación',
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

    public function edit(Quote $quote)
    {
        $this->emitTo('quote.edit-quote', 'openModal', $quote);
    }

    public function delete(Quote $quote)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó cotización con ID {$quote->id}"
        ]);

        $quote->delete();

        $this->emit('success', 'Cotización eliminada.');
    }

    public function turnIntoSellOrder(Quote $quote)
    {
        $this->emitTo('quote.turn-into-sell-order', 'openModal', $quote);
    }

    public function render()
    {
        $quotes = Quote::where('id', 'like', "%$this->search%")
            ->orWhere('customer_id', 'like', "%$this->search%")
            ->orWhereHas('creator', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orWhereHas('customer', function ($query) {
                $query->where('name', 'like', "%$this->search%");
            })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.quote.quotes', [
            'quotes' => $quotes,
        ]);
    }
}
