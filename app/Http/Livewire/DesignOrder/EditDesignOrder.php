<?php

namespace App\Http\Livewire\DesignOrder;

use App\Models\Customer;
use App\Models\DesignOrder;
use App\Models\DesignType;
use App\Models\MeasurementUnit;
use App\Models\MovementHistory;
use App\Models\User;
use App\Notifications\RequestApproved;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class EditDesignOrder extends Component
{
    use WithFileUploads;

    public $open = false,
        $design_order,
        $customer,
        $plans_image,
        $plans_image_id,
        $logo_image,
        $new_customer = 1,
        $logo_image_id;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $rules = [
        'design_order.design_name' => 'required',
        'design_order.design_data' => 'required',
        'design_order.especifications' => 'required',
        'design_order.designer_id' => 'required',
        'design_order.design_type_id' => 'required',
        'design_order.measurement_unit_id' => 'required',
        'design_order.dimentions' => 'required',
        'design_order.pantones' => 'max:150',
        'design_order.contact_name' => 'max:150',
        'design_order.customer_name' => 'max:150',
        'customer' => 'required',
        'design_order.contact_id' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
        'selected-customer' => 'selectedCustomer',
    ];

    public function mount()
    {
        $this->design_order = new DesignOrder();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'design_order',
            ]);
            $this->plans_image_id = rand();
            $this->logo_image_id = rand();
        }
    }

    public function openModal(DesignOrder $design_order)
    {
        $this->open = true;
        $this->design_order = $design_order;
        $this->customer = Customer::find($design_order->customer_id);
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
        $this->design_order->authorized_user_id = Auth::user()->id;
        $this->design_order->authorized_at = Carbon::now()->isoFormat('YYYY-MM-D hh:mm:ss');
        $this->design_order->status = 'Autorizado. Sin iniciar';
        $this->design_order->save();

        // notify to request's creator
        $this->design_order->creator->notify(new RequestApproved('solicitud de diseño', $this->design_order->id, 'design-orders'));

        $this->resetExcept([
            'design_order',
        ]);

        $this->emit('success', 'OD autorizada.');
        $this->emitTo('design-order.design-orders', 'render');
    }

    public function update()
    {
        if (!$this->new_customer) {
            unset($this->rules['design_order.customer_name']);
            unset($this->rules['design_order.contact_name']);
        } else {
            unset($this->rules['customer']);
            unset($this->rules['design_order.contact_id']);
        }

        if ($this->plans_image) {
            $this->rules['plans_image'] = 'image';
        }

        if ($this->logo_image) {
            $this->rules['logo_image'] = 'image';
        }

        $this->validate();

        if (!$this->new_customer) {
            $this->design_order->customer_id = $this->customer->id;
            $this->design_order->customer_name = null;
            $this->design_order->contact_name = null;
        } else {
            $this->design_order->customer_id = null;
            $this->design_order->contact_id = null;
        }

        if ($this->plans_image) {
            Storage::delete($this->design_order->plans_image);
            //storage optimized image
            $image_name = time() . Str::random(10) . '.' . $this->plans_image->extension();
            $image_path = storage_path() . "/app/public/design-plans/$image_name";
            Image::make($this->plans_image)
                ->save($image_path, 40);
            $this->design_order->plans_image = "public/design-plans/$image_name";
        }

        if ($this->logo_image) {
            Storage::delete($this->design_order->logo_image);
            //storage optimized image
            $image_name = time() . Str::random(10) . '.' . $this->logo_image->extension();
            $image_path = storage_path() . "/app/public/design-logos/$image_name";
            Image::make($this->logo_image)
                ->save($image_path, 40);
            $this->design_order->logo_image = "public/design-logos/$image_name";
        }

        unset($this->design_order['customer']);

        $this->design_order->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó orden de diseño con ID: {$this->design_order->id}"
        ]);

        $this->resetExcept([
            'design_order',
        ]);
        $this->plans_image_id = rand();
        $this->logo_image_id = rand();

        $this->emitTo('design-order.design-orders', 'render');
        $this->emit('success', 'Orden de diseño actualizada');
    }

    public function render()
    {
        return view('livewire.design-order.edit-design-order', [
            'design_types' => DesignType::all(),
            'designers' => User::role('Diseñador')->get(),
            'units' => MeasurementUnit::all(),
        ]);
    }
}
