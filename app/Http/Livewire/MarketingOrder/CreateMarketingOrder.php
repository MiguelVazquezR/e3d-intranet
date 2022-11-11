<?php

namespace App\Http\Livewire\MarketingOrder;

use App\Mail\ApproveMailable;
use App\Models\Customer;
use App\Models\MarketingOrder;
use App\Models\MovementHistory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMarketingOrder extends Component
{
    use WithFileUploads;

    public $open = false,
        $order_name,
        $customer_name,
        $customer,
        $especifications,
        $order_type = "",
        $new_customer = 1;

    protected $rules = [
        'order_name' => 'required|string|max:191',
        'especifications' => 'required',
        'order_type' => 'required',
    ];

    protected $listeners = [
        'render',
        'selected-customer' => 'selectedCustomer',
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

    public function selectedCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function store()
    {
        if ($this->new_customer) {
            $this->rules['customer_name'] = 'required';
        } else {
            $this->rules['customer'] = 'required';
        }

        $validated_data = $this->validate();

        $aditional_data = [
            'user_id' => auth()->id(),
        ];

        if (!$this->new_customer) {
            $aditional_data['customer_id'] = $this->customer->id;
        }

        $marketing_order = MarketingOrder::create($validated_data + $aditional_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => auth()->id(),
            'description' => "Se agregó nueva orden de mercadotecnia con ID: {$marketing_order->id}"
        ]);

        if (auth()->user()->can('autorizar_ordenes_mercadotecnia')) {
            $marketing_order->authorized_user_id = auth()->id();
            $marketing_order->authorized_at = now()->isoFormat('YYYY-MM-D hh:mm:ss');
            $marketing_order->status = 'Sin iniciar';
        } else {
            // send email notification
            if (App::environment('production'))
                Mail::to('maribel@emblemas3d.com')
                    // ->bcc('miguelvz26.mv@gmail.com')
                    ->queue(new ApproveMailable('Orden de mercadotecnia', $marketing_order->id, MarketingOrder::class));
        }

        $marketing_order->save();

        $this->reset();

        $this->emitTo('marketing-order.marketing-orders', 'render');
        $this->emit('success', 'Nueva orden de mercadotecnia creada');
    }

    public function render()
    {
        return view('livewire.marketing-order.create-marketing-order');
    }
}
