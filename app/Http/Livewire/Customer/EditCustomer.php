<?php

namespace App\Http\Livewire\Customer;

use App\Models\Company;
use App\Models\CompanyHasProductForSell;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\MovementHistory;
use App\Models\SatMethod;
use App\Models\SatType;
use App\Models\SatWay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditCustomer extends Component
{
    public $open = false,
        $company,
        $active_tab = 0,
        $name,
        $address,
        $branch_post_code,
        $sat_method_id,
        $sat_way_id,
        $sat_type_id,
        $branch_list = [],
        $contact_list = [],
        $temporary_contacts_deleted_list = [],
        $temporary_branches_deleted_list = [],
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
        'company.bussiness_name' => 'required|max:60',
        'company.phone' => 'required|max:20',
        'company.rfc' => 'required|max:20',
        'company.fiscal_address' => 'required|max:191',
        'company.post_code' => 'required|numeric',
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
        'openModal',
        'delete',
        'edit',
        'deleteCompositProduct',
    ];

    public function mount()
    {
        $this->company = new Company();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'company',
            ]);
        }
    }

    public function openCreateSatMethod()
    {
        $this->emitTo('currency.create-currency', 'openModal');
    }

    public function openModal(Company $company)
    {
        $this->company = $company;
        $this->open = true;

        foreach ($this->company->customers as $customer) {
            $contacts_list = [];
            foreach ($customer->contacts as $i => $contact) {
                $contacts_list[] = $contact->toArray();
                if ($contact->birth_date) {
                    $birth_date = $contact->birth_date->isoFormat('YYYY-MM-DD');
                    $contacts_list[$i]["birth_date"] = $birth_date;
                }else {
                    $contacts_list[$i]["birth_date"] = '2021-01-01';
                }
            }
            $this->branch_list[] = [
                'branch' => $customer->toArray(),
                'contacts' => $contacts_list,
            ];
        }
    }

    public function editProductForSell(CompanyHasProductForSell $product_for_sell)
    {
        $this->emitTo('company-has-product-for-sell.edit-company-has-product-for-sell', 'openModal', $product_for_sell);
    }

    public function updatePriceProductForSell(CompanyHasProductForSell $product_for_sell)
    {
        $this->emitTo('company-has-product-for-sell.edit-price-product-for-sell', 'openModal', $product_for_sell);
    }

    public function deleteProductForSell(CompanyHasProductForSell $product_for_sell)
    {
        $product_for_sell->delete();

        $this->emitTo('customer.customers', 'edit', $this->company);
        $this->emit('success', 'Producto para venta eliminado');
    }

    public function update()
    {
        $this->validate(null, [
            'branch_list.required' => 'Debe de haber mínimo una sucursal agregada',
        ]);

        $this->company->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó compañía (cliente) con ID: {$this->company->id}"
        ]);

        // create new branches & contacts, and update olds
        foreach ($this->branch_list as $branch) {
            if (array_key_exists('id', $branch['branch'])) {
                foreach ($branch['contacts'] as $contact) {
                    if (array_key_exists('id', $contact)) {
                        $contact["birth_date"] = explode('T', $contact["birth_date"])[0];
                        Contact::find($contact["id"])
                            ->update($contact);
                    } else {
                        $contact["birth_date"] = explode('T', $contact["birth_date"])[0];
                        $contact["model_id"] = $branch['branch']['id'];
                        $contact["model_name"] = Customer::class;
                        Contact::create($contact);
                    }
                }
                Customer::find($branch['branch']["id"])
                    ->update($branch['branch']);
            } else {
                $branch["branch"]["company_id"] = $this->company->id;
                $new_branch = Customer::create($branch["branch"]);

                foreach ($branch['contacts'] as $contact) {
                    $contact["birth_date"] = explode('T', $contact["birth_date"])[0];
                    $contact["model_id"] = $new_branch->id;
                    $contact["model_name"] = Customer::class;
                    Contact::create($contact);
                }
            }
        }

        // delete old branches & contacts on temporary list
        Customer::whereIn('id', $this->temporary_branches_deleted_list)->delete();
        Contact::whereIn('id', $this->temporary_contacts_deleted_list)->delete();

        $this->resetExcept([
            'company',
        ]);

        $this->emit('success', 'Cliente actualizado');
    }

    // listing methods -----------------------------------

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

        $contact = new Contact([
            'name' => $this->contact_name,
            'phone' => $this->contact_phone,
            'email' => $this->email,
            'birth_date' => '2021-' . $this->month . '-' . $this->day,
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

        if (array_key_exists('id', $this->branch_list[$this->edit_branch_index]["branch"])) {
            $branch = new Customer($validated_data + [
                'id' => $this->branch_list[$this->edit_branch_index]["branch"]["id"],
                'company_id' => $this->company->id,
            ]);
        } else {
            $branch = new Customer($validated_data + [
                'company_id' => $this->company->id,
            ]);
        }

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

        if (array_key_exists('id', $this->contact_list[$this->edit_contact_index])) {
            $contact = new Contact([
                'id' => $this->contact_list[$this->edit_contact_index]["id"],
                'name' => $this->contact_name,
                'phone' => $this->contact_phone,
                'email' => $this->email,
                'birth_date' => '2021-' . $this->month . '-' . $this->day,
            ]);
        } else {
            $contact = new Contact([
                'name' => $this->contact_name,
                'phone' => $this->contact_phone,
                'email' => $this->email,
                'birth_date' => '2021-' . $this->month . '-' . $this->day,
            ]);
        }
        $this->contact_list[$this->edit_contact_index] = $contact->toArray();
        $this->contact_list[$this->edit_contact_index]["birth_date"] = $contact->birth_date->isoFormat('YYYY-MM-DD');
        $this->resetContact();
        // dd($this->contact_list);
    }

    public function editBranch($index)
    {
        $this->name = $this->branch_list[$index]['branch']["name"];
        $this->address = $this->branch_list[$index]['branch']["address"];
        $this->branch_post_code = $this->branch_list[$index]['branch']["post_code"];
        $this->sat_method_id = $this->branch_list[$index]['branch']["sat_method_id"];
        $this->sat_way_id = $this->branch_list[$index]['branch']["sat_way_id"];
        $this->sat_type_id = $this->branch_list[$index]['branch']["sat_type_id"];
        $this->contact_list = [];
        $this->contact_list = $this->branch_list[$index]['contacts'];
        $this->edit_branch_index = $index;
    }

    public function editContact($index)
    {
        $contact = $this->contact_list[$index];
        $this->contact_name = $contact["name"];
        $this->contact_phone = $contact["phone"];
        $this->email = $contact["email"];
        if ($contact["birth_date"]) {
            $date = new Carbon($contact["birth_date"]);
            $this->day = $date->isoFormat('D');
            $this->month = $date->isoFormat('M');
        }
        $this->edit_contact_index = $index;
    }

    public function closeEditBranch()
    {
        $this->edit_contact_index = null;
        $this->contact_list = [];
        $this->resetContact();
        $this->resetBranch();
    }

    public function closeEditContact()
    {
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
            'add_contact',
        ]);
    }

    public function addToTemporaryDeletedList($list, $id)
    {
        if ($list == 'branch') {
            $this->temporary_branches_deleted_list[] = $id;
        } else {
            $this->temporary_contacts_deleted_list[] = $id;
        }
    }

    public function removeFromTemporaryBranchesDeletedList($id)
    {
        $index = array_search($id, $this->temporary_branches_deleted_list);
        unset($this->temporary_branches_deleted_list[$index]);
    }

    public function removeFromTemporaryContactsDeletedList($id)
    {
        $index = array_search($id, $this->temporary_contacts_deleted_list);
        unset($this->temporary_contacts_deleted_list[$index]);
    }

    public function deleteBranch($index)
    {
        if (array_key_exists('id', $this->branch_list[$index]['branch'])) {
            $this->addToTemporaryDeletedList('branch', $this->branch_list[$index]['branch']['id']);
        } else {
            unset($this->branch_list[$index]);
        }
    }

    public function deleteContact($index)
    {
        if (array_key_exists('id', $this->contact_list[$index])) {
            $this->addToTemporaryDeletedList('contact', $this->contact_list[$index]["id"]);
        } else {
            unset($this->contact_list[$index]);
        }
    }

    // ---------------------------------------------------

    public function render()
    {
        return view('livewire.customer.edit-customer', [
            'sat_methods' => SatMethod::all(),
            'sat_ways' => SatWay::all(),
            'sat_types' => SatType::all(),
        ]);
    }
}
