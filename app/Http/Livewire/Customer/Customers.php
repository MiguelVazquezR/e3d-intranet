<?php

namespace App\Http\Livewire\Customer;

use App\Models\Company;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
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
        'bussiness_name' => 'Razón social',
        'rfc' => 'RFC',
        'post_code' => 'C.P.',
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

    public function show(Company $company)
    {
        $this->emitTo('customer.show-customer', 'openModal', $company);
    }

    public function edit(Company $company)
    {
        $this->emitTo('customer.edit-customer', 'openModal', $company);
    }

    public function delete(Company $company)
    {
        // create movement history
        MovementHistory::create([
            'movement_type' => 3,
            'user_id' => Auth::user()->id,
            'description' => "Se eliminó compañía (cliente) de nombre: {$company->bussiness_name}"
        ]);
        
        $company->delete();


        $this->emit('success', 'Cliente eliminado.');
    }

    public function render()
    {
        $companies = Company::where('id', 'like', "%$this->search%")
            ->orWhere('bussiness_name', 'like', "%$this->search%")
            ->orWhere('post_code', 'like', "%$this->search%")
            ->orWhere('rfc', 'like', "%$this->search%")
            // ->orWhereHas('customers', function ($query) {
            //     $query->where('bussiness_name', 'like', "%$this->search%");
            // })
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->elements);
        return view('livewire.customer.customers', [
            'companies' => $companies,
        ]);
    }
}
