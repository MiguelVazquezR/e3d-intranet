<?php

namespace App\Http\Livewire\Supplier;

use App\Models\BankData;
use App\Models\Contact;
use App\Models\MovementHistory;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateSupplier extends Component
{
    public $open = false,
        $name,
        $post_code,
        $address,
        $contact_list = [],
        $bank_data_list = [],
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
        'name' => 'required|max:60',
        'address' => 'required',
        'post_code' => 'required|numeric',
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
            'contact_list.required' => 'Debe de haber mínimo un contacto agregado',
        ]);

        $supplier = Supplier::create($validated_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó nuevo proveedor de nombre: {$supplier->name}"
        ]);

        // create contacts
        foreach ($this->contact_list as $contact) {
            $contact["contactable_id"] = $supplier->id;
            $contact["contactable_type"] = Supplier::class;
            $contact["birth_date"] = explode('T', $contact["birth_date"])[0];
            Contact::create($contact);
        }

        // create bank data
        foreach ($this->bank_data_list as $bank_data) {
            $bank_data["supplier_id"] = $supplier->id;
            BankData::create($bank_data);
        }

        $this->reset();

        $this->emitTo('supplier.suppliers', 'render');
        $this->emit('success', 'Nuevo proveedor creado');
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

        $contact = new Contact([
            'name' => $this->contact_name,
            'phone' => $this->contact_phone,
            'email' => $this->email,
            'birth_date' => '2021-' . $this->month . '-' . $this->day,
        ]);

        $this->contact_list[$this->edit_contact_index] = $contact->toArray();
        $this->contact_list[$this->edit_contact_index]["birth_date"] = $contact->birth_date->isoFormat('YYYY-MM-DD');

        $this->resetContact();
    }

    public function updateBankDataFromList()
    {
        $this->validate($this->bank_data_rules);

        $bank_data = new BankData([
            'beneficiary_name' => $this->beneficiary_name,
            'account' => $this->account,
            'CLABE' => $this->CLABE,
            'bank' => $this->bank,
        ]);

        $this->bank_data_list[$this->edit_bank_data_index] = $bank_data->toArray();

        $this->resetBankData();
    }

    public function editContact($index)
    {
        $contact = new Contact($this->contact_list[$index]);
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
        $bank_data = new BankData($this->bank_data_list[$index]);
        $this->beneficiary_name = $this->bank_data_list[$index]["beneficiary_name"];
        $this->account = $this->bank_data_list[$index]["account"];
        $this->CLABE = $this->bank_data_list[$index]["CLABE"];
        $this->bank = $this->bank_data_list[$index]["bank"];

        $this->edit_bank_data_index = $index;
    }

    public function closeEditContact()
    {
        $this->edit_contact_index = null;
        $this->resetContact();
    }

    public function closeEditBankData()
    {
        $this->edit_bank_data_index = null;
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
        ]);
    }

    public function deleteContact($index)
    {
        unset($this->contact_list[$index]);
    }

    public function deleteBankData($index)
    {
        unset($this->bank_data_list[$index]);
    }

    public function render()
    {
        return view('livewire.supplier.create-supplier');
    }
}
