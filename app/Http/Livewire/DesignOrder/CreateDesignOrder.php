<?php

namespace App\Http\Livewire\DesignOrder;

use App\Mail\ApproveMailable;
use App\Models\Customer;
use App\Models\DesignOrder;
use App\Models\DesignType;
use App\Models\MeasurementUnit;
use App\Models\MovementHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CreateDesignOrder extends Component
{
    use WithFileUploads;

    public $open = false,
        $design_name,
        $plans_image,
        $plans_image_id,
        $logo_image,
        $logo_image_id,
        $customer_name,
        $customer,
        $contact_name,
        $contact_id,
        $design_data,
        $especifications,
        $pantones,
        $designer_id,
        $design_type_id,
        $dimentions,
        $measurement_unit_id,
        $new_customer = 1;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $rules = [
        'design_name' => 'required',
        'design_data' => 'required',
        'especifications' => 'required',
        'designer_id' => 'required',
        'design_type_id' => 'required',
        'measurement_unit_id' => 'required',
        'dimentions' => 'required',
    ];

    protected $listeners = [
        'render',
        'selected-customer' => 'selectedCustomer',
    ];

    public function mount()
    {
        $this->plans_image_id = rand();
        $this->logo_image_id = rand();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
            $this->plans_image_id = rand();
            $this->logo_image_id = rand();
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
            $this->rules['contact_name'] = 'required';
        } else {
            $this->rules['customer'] = 'required';
            $this->rules['contact_id'] = 'required';
        }

        if ($this->plans_image) {
            $this->rules['plans_image'] = 'image';
        }

        if ($this->logo_image) {
            $this->rules['logo_image'] = 'image';
        }

        $validated_data = $this->validate();

        $aditional_data = [
            'user_id' => Auth::user()->id,
            'pantones' => $this->pantones,
        ];

        if (!$this->new_customer) {
            $aditional_data['customer_id'] = $this->customer->id;
        }

        $design_order = DesignOrder::create($validated_data + $aditional_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se agregó nueva orden de diseño con ID: {$design_order->id}"
        ]);

        if ($this->plans_image) {
            //storage optimized image
            $image_name = time() . Str::random(10) . '.' . $this->plans_image->extension();
            $image_path = storage_path() . "/app/public/design-plans/$image_name";
            Image::make($this->plans_image)
                ->save($image_path, 40);
            $design_order->plans_image = "public/design-plans/$image_name";
        }

        if ($this->logo_image) {
            //storage optimized image
            $image_name = time() . Str::random(10) . '.' . $this->logo_image->extension();
            $image_path = storage_path() . "/app/public/design-logos/$image_name";
            Image::make($this->logo_image)
                ->save($image_path, 40);
            $design_order->logo_image = "public/design-logos/$image_name";
        }

        if (Auth::user()->can('autorizar_ordenes_diseño')) {
            $design_order->authorized_user_id = Auth::user()->id;
            $design_order->authorized_at = Carbon::now()->isoFormat('YYYY-MM-D hh:mm:ss');
            $design_order->status = 'Sin iniciar';
        } else {
            // send email notification
            Mail::to('maribel@emblemas3d.com')
                // ->bcc('miguelvz26.mv@gmail.com')
                ->queue(new ApproveMailable('Orden de diseño', $design_order->id, DesignOrder::class));
        }

        $design_order->save();

        $this->reset();

        $this->emitTo('design-order.design-orders', 'render');
        $this->emit('success', 'Nueva orden de diseño creada');
    }

    public function render()
    {
        return view('livewire.design-order.create-design-order', [
            'design_types' => DesignType::all(),
            'designers' => User::role('Diseñador')->where('active', true)->get(),
            'units' => MeasurementUnit::all(),
        ]);
    }
}
