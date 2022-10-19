<?php

namespace App\Http\Livewire\Customer;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\MovementHistory;
use App\Models\SatMethod;
use App\Models\SatType;
use App\Models\SatWay;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateCustomer extends Component
{
    public $open = false,
        $bussiness_name,
        $phone,
        $rfc,
        $fiscal_address,
        $post_code,
        $name,
        $address,
        $branch_post_code,
        $sat_method_id,
        $sat_way_id,
        $sat_type_id,
        $branch_list = [],
        $contact_list = [],
        $add_contact = false,
        $add_branch = false,
        $edit_branch_index = null,
        $edit_contact_index = null,
        $contact_name,
        $email,
        $contact_phone,
        $day,
        $month,
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

    protected $rules = [
        'bussiness_name' => 'required|max:60',
        'phone' => 'required|max:20',
        'rfc' => 'required|max:20',
        'fiscal_address' => 'required|max:191',
        'post_code' => 'required|numeric',
        'branch_list' => 'required',
    ];

    protected $branch_rules = [
        'name' => 'required',
        'address' => 'required',
        'branch_post_code' => 'required|numeric',
        'sat_method_id' => 'required',
        'sat_way_id' => 'required',
        'sat_type_id' => 'required',
        'contact_list' => 'required',
    ];

    protected $contact_rules = [
        'contact_name' => 'required',
        'email' => 'required',
        'contact_phone' => 'required|numeric',
    ];

    protected $listeners = [
        'render',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function store()
    {
        $validated_data = $this->validate(null, [
            'branch_list.required' => 'Debe de haber mínimo una sucursal agregada'
        ]);

        $company = Company::create($validated_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó nueva compañía (cliente) de nombre: {$company->bussiness_name}"
        ]);

        // create customers (branches)
        foreach ($this->branch_list as $branch) {
            $branch["branch"]["company_id"] = $company->id;
            $new_branch = Customer::create($branch["branch"]);

            // create contacts
            foreach ($branch["contacts"] as $contact) {
                $contact["birth_date"] = explode('T', $contact["birth_date"])[0];
                $contact["contactable_id"] = $new_branch->id;
                $contact["contactable_type"] = Customer::class;
                Contact::create($contact);
            }
        }

        $this->reset();

        $this->emitTo('customer.customers', 'render');
        $this->emit('success', 'Nuevo cliente creado');
    }

    public function addBranchToList()
    {
        $validated_data = $this->validate($this->branch_rules, [
            'contact_list.required' => 'Debe de haber mínimo un contacto agregado'
        ]);

        // change key for good storing data
        unset($validated_data['branch_post_cost']);
        $validated_data['post_code'] = $this->branch_post_code;

        $branch = new Customer($validated_data + ['company_id' => 0]);

        $this->branch_list[] = [
            'branch' => $branch->toArray(),
            'contacts' => $this->contact_list,
        ];

        $this->contact_list = [];
        $this->resetBranch();
        $this->resetContact();
    }

    public function addContactToList()
    {
        $this->validate($this->contact_rules);

        if(!$this->month) $this->month = 1;
        if(!$this->day) $this->day = 1;

        $contact = new Contact([
            'name' => $this->contact_name,
            'phone' => $this->contact_phone,
            'email' => $this->email,
            'birth_date' => '2021-'.$this->month.'-'.$this->day,
        ]);

        $this->contact_list[] = $contact->toArray();

        $this->resetContact();
    }

    public function updateBranchFromList()
    {
        $validated_data = $this->validate($this->branch_rules);

        // change key for good storing data
        unset($validated_data['branch_post_cost']);
        $validated_data['post_code'] = $this->branch_post_code;

        $branch = new Customer($validated_data + ['company_id' => 0]);

        $this->branch_list[$this->edit_branch_index] = [
            'branch' => $branch->toArray(),
            'contacts' => $this->contact_list,
        ];

        $this->contact_list = [];
        $this->resetBranch();
        $this->resetContact();
    }

    public function updateContactFromList()
    {
        $this->validate($this->contact_rules);

        $contact = new Contact([
            'name' => $this->contact_name,
            'phone' => $this->contact_phone,
            'email' => $this->email,
            'birth_date' => '2021-'.$this->month.'-'.$this->day,
        ]);

        $this->contact_list[$this->edit_contact_index] = $contact->toArray();
        $this->contact_list[$this->edit_contact_index]["birth_date"] = $contact->birth_date->isoFormat('YYYY-MM-DD');

        $this->resetContact();
    }
    
    public function editBranch($index)
    {
        $this->name = $this->branch_list[$index]['branch']["name"];
        $this->address = $this->branch_list[$index]['branch']["address"];
        $this->branch_post_code = $this->branch_list[$index]['branch']["post_code"];
        $this->sat_method_id = $this->branch_list[$index]['branch']["sat_method_id"];
        $this->sat_way_id = $this->branch_list[$index]['branch']["sat_way_id"];
        $this->sat_type_id = $this->branch_list[$index]['branch']["sat_type_id"];
        $this->contact_list = $this->branch_list[$index]['contacts'];
        $this->edit_branch_index = $index;

    }
    
    public function editContact($index)
    {
        $contact = new Contact($this->contact_list[$index]);
        $this->contact_name = $this->contact_list[$index]["name"];
        $this->contact_phone = $this->contact_list[$index]["phone"];
        $this->email = $this->contact_list[$index]["email"];
        if($contact->birth_date->isoFormat('YYYY') == '2021' ) {
            $this->day = $contact->birth_date->isoFormat('D');
            $this->month = $contact->birth_date->isoFormat('M');
        }
        $this->edit_contact_index = $index;
    }
    
    public function closeEditBranch()
    {
        $this->edit_branch_index = null;
        $this->contact_list = [];
        $this->resetContact();
        $this->resetBranch();

    }

    public function closeEditContact()
    {
        $this->edit_contact_index = null;
        $this->resetContact();

    }

    public function resetBranch()
    {
        $this->reset([
            'name',
            'address',
            'branch_post_code',
            'sat_method_id',
            'sat_way_id',
            'sat_type_id',
            'edit_branch_index',
            'add_branch',
        ]);
    }

    public function resetContact()
    {
        $this->reset([
            'contact_name',
            'contact_phone',
            'email',
            'day',
            'month',
            'edit_contact_index',
        ]);
    }

    public function deleteBranch($index)
    {
        unset($this->branch_list[$index]);
    }
    
    public function deleteContact($index)
    {
        unset($this->contact_list[$index]);
    }

    public function render()
    {
        return view('livewire.customer.create-customer', [
            'sat_methods' => SatMethod::all(),
            'sat_ways' => SatWay::all(),
            'sat_types' => SatType::all(),
        ]);
    }
}
