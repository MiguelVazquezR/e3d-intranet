<?php

namespace App\Http\Livewire\MarketingOrder;

use App\Models\Customer;
use App\Models\MarketingOrder;
use App\Models\MovementHistory;
use App\Notifications\RequestApproved;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditMarketingOrder extends Component
{
    use WithFileUploads;

    public $open = false,
        $marketing_order,
        $customer,
        $plans_image,
        $plans_image_id,
        $logo_image,
        $new_customer = 1,
        $logo_image_id;

    protected $rules = [
        'marketing_order.order_name' => 'required',
        'marketing_order.especifications' => 'required',
        'marketing_order.order_type' => 'required',
        'marketing_order.customer_name' => 'max:150',
        'customer' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-customer' => 'selectedCustomer',
    ];

    public function mount()
    {
        $this->marketing_order = new MarketingOrder();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'marketing_order',
            ]);
        }
    }

    public function openModal(MarketingOrder $marketing_order)
    {
        $this->open = true;
        $this->marketing_order = $marketing_order;
        $this->customer = Customer::find($marketing_order->customer_id);
        if ($this->customer) {
            $this->new_customer = 0;
        }
    }

    public function selectedCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function authorize()
    {
        $this->marketing_order->authorized_user_id = auth()->id();
        $this->marketing_order->authorized_at = now()->isoFormat('YYYY-MM-D hh:mm:ss');
        $this->marketing_order->status = 'Autorizado. Sin iniciar';
        $this->marketing_order->save();

        // notify to request's creator
        $this->marketing_order->creator->notify(new RequestApproved('solicitud de mercadotecnia', $this->marketing_order->id, 'marketing-orders'));
        $this->resetExcept([
            'marketing_order',
        ]);

        $this->emit('success', 'Orden autorizada.');
        $this->emitTo('marketing-order.marketing-orders', 'render');
    }

    public function update()
    {
        if (!$this->new_customer) {
            unset($this->rules['marketing_order.customer_name']);
        } else {
            unset($this->rules['customer']);
        }

        $this->validate();

        if (!$this->new_customer) {
            $this->marketing_order->customer_id = $this->customer->id;
            $this->marketing_order->customer_name = null;
        } else {
            $this->marketing_order->customer_id = null;
        }

        unset($this->marketing_order['customer']);

        $this->marketing_order->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => auth()->id(),
            'description' => "Se editó orden de mercadotecnia con ID: {$this->marketing_order->id}"
        ]);

        $this->resetExcept([
            'marketing_order',
        ]);

        $this->emitTo('marketing-order.marketing-orders', 'render');
        $this->emit('success', 'Orden de mercadotecnia actualizada');
    }
    
    public function render()
    {
        return view('livewire.marketing-order.edit-marketing-order');
    }
}
