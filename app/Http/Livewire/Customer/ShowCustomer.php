<?php

namespace App\Http\Livewire\Customer;

use App\Models\Company;
use Livewire\Component;

class ShowCustomer extends Component
{
    public $company,
        $open = false,
        $active_tab = 0;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->company = new Company();
    }

    public function openModal(Company $company)
    {
        $this->company = $company;
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.customer.show-customer');
    }
}
