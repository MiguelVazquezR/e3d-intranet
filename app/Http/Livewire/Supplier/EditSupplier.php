<?php

namespace App\Http\Livewire\Supplier;

use App\Models\BankData;
use App\Models\Contact;
use App\Models\MovementHistory;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditSupplier extends Component
{
    public $open = false,
        $supplier,
        $name,
        $address,
        $contact_list = [],
        $bank_data_list = [],
        $temporary_contacts_deleted_list = [],
        $temporary_bank_data_deleted_list = [],
        $add_contact = false,
        $add_bank_data = false,
        $edit_contact_index = null,
        $edit_bank_data_index = null,
        $contact_name,
        $email,
        $contact_phone,
        $beneficiary_name,
        $account,
        $CLABE,
        $bank,
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
        'supplier.name' => 'required|max:60',
        'supplier.address' => 'required|max:191',
        'supplier.post_code' => 'required|numeric',
        'contact_list' => 'required',
    ];

    protected $contact_rules = [
        'contact_name' => 'required',
        'email' => 'required',
        'contact_phone' => 'required|numeric',
    ];

    protected $bank_data_rules = [
        'beneficiary_name' => 'required',
        'account' => 'required|numeric',
        'CLABE' => 'required|numeric',
        'bank' => 'required',
    ];

    protected $listeners = [
        'openModal',
        'delete',
        'edit',
    ];

    public function mount()
    {
        $this->supplier = new Supplier();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'supplier',
            ]);
        }
    }

    public function openModal(supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->open = true;

        // load contacts
        foreach ($supplier->contacts as $i => $contact) {
            if ($contact->model_name == Supplier::class) {
                $birth_date = $contact->birth_date->isoFormat('YYYY-MM-DD');
                $this->contact_list[] = $contact->toArray();
                end($this->contact_list)["birth_date"] = $birth_date;
            }
        }

        // load bank data
        foreach ($supplier->bankAccounts as $i => $bank_data) {
            $this->bank_data_list[] = $bank_data->toArray();
        }
    }

    public function update()
    {
        $this->validate(null, [
            'contact_list.required' => 'Debe de haber mínimo un contacto agregado',
        ]);

        $this->supplier->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó proveedor con ID: {$this->supplier->id}"
        ]);

        // create new contacts, and update olds
        foreach ($this->contact_list as $contact) {
            $contact["birth_date"] = explode('T', $contact["birth_date"])[0];
            if (array_key_exists('id', $contact)) {
                Contact::find($contact["id"])
                    ->update($contact);
            } else {
                $contact["model_id"] = $this->supplier->id;
                $contact["model_name"] = Supplier::class;
                Contact::create($contact);
            }
        }

        // create new bank data, and update olds
        foreach ($this->bank_data_list as $bank_data) {
            if (array_key_exists('id', $bank_data)) {
                BankData::find($bank_data["id"])
                    ->update($bank_data);
            } else {
                $bank_data["supplier_id"] = $this->supplier->id;
                BankData::create($bank_data);
            }
        }

        // delete old bank data & contacts on temporary list
        Contact::whereIn('id', $this->temporary_contacts_deleted_list)->delete();
        BankData::whereIn('id', $this->temporary_bank_data_deleted_list)->delete();

        $this->resetExcept([
            'supplier',
        ]);

        $this->emitTo('supplier.suppliers', 'render');
        $this->emit('success', 'Proveedor actualizado');
    }

    // listing methods -----------------------------------
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

    public function addBankDataToList()
    {
        $this->validate($this->bank_data_rules);
        $bank_data = new BankData([
            'beneficiary_name' => $this->beneficiary_name,
            'account' => $this->account,
            'CLABE' => $this->CLABE,
            'bank' => $this->bank,
        ]);

        $this->bank_data_list[] = $bank_data->toArray();

        $this->resetBankData();
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
    }

    public function updateBankDataFromList()
    {
        $this->validate($this->bank_data_rules);

        if (array_key_exists('id', $this->bank_data_list[$this->edit_bank_data_index])) {
            $bank_data = new BankData([
                'id' => $this->bank_data_list[$this->edit_bank_data_index]["id"],
                'beneficiary_name' => $this->beneficiary_name,
                'account' => $this->account,
                'CLABE' => $this->CLABE,
                'bank' => $this->bank,
            ]);
        } else {
            $bank_data = new BankData([
                'beneficiary_name' => $this->beneficiary_name,
                'account' => $this->account,
                'CLABE' => $this->CLABE,
                'bank' => $this->bank,
            ]);
        }

        $this->bank_data_list[$this->edit_bank_data_index] = $bank_data->toArray();

        $this->resetBankData();
    }

    public function editContact($index)
    {
        $contact = Contact::find($this->contact_list[$index]["id"]);
        $this->contact_name = $this->contact_list[$index]["name"];
        $this->contact_phone = $this->contact_list[$index]["phone"];
        $this->email = $this->contact_list[$index]["email"];
        if ($contact->birth_date->isoFormat('YYYY') == '2021') {
            $this->day = $contact->birth_date->isoFormat('D');
            $this->month = $contact->birth_date->isoFormat('M');
        }
        $this->edit_contact_index = $index;
    }

    public function editBankData($index)
    {
        $this->beneficiary_name = $this->bank_data_list[$index]["beneficiary_name"];
        $this->account = $this->bank_data_list[$index]["account"];
        $this->CLABE = $this->bank_data_list[$index]["CLABE"];
        $this->bank = $this->bank_data_list[$index]["bank"];

        $this->edit_bank_data_index = $index;
    }


    public function closeEditContact()
    {
        $this->resetContact();
    }

    public function closeEditBankData()
    {
        $this->resetBankData();
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

    public function resetBankData()
    {
        $this->reset([
            'beneficiary_name',
            'account',
            'CLABE',
            'bank',
            'edit_bank_data_index',
            'add_bank_data',
        ]);
    }

    // public function addToTemporaryDeletedList($id, $contact = true)
    // {
    //     if ($contact)
    //         $this->temporary_contacts_deleted_list[] = $id;
    //     else
    //         $this->temporary_bank_data_deleted_list[] = $id;
    // }

    public function removeFromTemporaryContactsDeletedList($id)
    {
        $index = array_search($id, $this->temporary_contacts_deleted_list);
        unset($this->temporary_contacts_deleted_list[$index]);
    }
    
    public function removeFromTemporaryBankDataDeletedList($id)
    {
        $index = array_search($id, $this->temporary_bank_data_deleted_list);
        unset($this->temporary_bank_data_deleted_list[$index]);
    }

    public function deleteContact($index)
    {
        if (array_key_exists('id', $this->contact_list[$index])) {
            $this->temporary_contacts_deleted_list[] = $this->contact_list[$index]["id"];
        } else {
            unset($this->contact_list[$index]);
        }
    }
    
    public function deleteBankData($index)
    {
        if (array_key_exists('id', $this->bank_data_list[$index])) {
            $this->temporary_bank_data_deleted_list[] = $this->bank_data_list[$index]["id"];
        } else {
            unset($this->bank_data_list[$index]);
        }
    }
    // ---------------------------------------------------

    public function render()
    {
        return view('livewire.supplier.edit-supplier');
    }
}
