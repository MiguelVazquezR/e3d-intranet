<?php

namespace App\Http\Livewire\Supplier;

use App\Models\MovementHistory;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Suppliers extends Component
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
        'name' => 'Nombre',
        'address' => 'dirección',
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

    public function show(Supplier $supplier)
    {
        $this->emitTo('supplier.show-supplier', 'openModal', $supplier);
    }

    public function edit(supplier $supplier)
    {
        $this->emitTo('supplier.edit-supplier', 'openModal', $supplier);
    }

    public function delete(supplier $supplier)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó proveedor de nombre: {$supplier->name}"
        ]);
        
        $supplier->delete();


        $this->emit('success', 'Proveedor eliminado.');
    }

    public function render()
    {
        $suppliers = Supplier::where('id', 'like', "%$this->search%")
            ->orWhere('name', 'like', "%$this->search%")
            ->orWhere('post_code', 'like', "%$this->search%")
            ->orWhere('address', 'like', "%$this->search%")
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.supplier.suppliers', [
            'suppliers' => $suppliers,
        ]);
    }

}
