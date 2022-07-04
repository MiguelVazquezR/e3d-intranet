<?php

namespace App\Http\Livewire\DesignResult;

use App\Models\DesignOrder;
use App\Models\DesignResult;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class CreateDesignResult extends Component
{
    use WithFileUploads;

    public $open = false,
        $design_order,
        $image_id,
        $image,
        $notes,
        $emit_response_to;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $rules = [
        'image' => 'required|image',
        'notes' => 'max:300',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
            $this->image_id = rand();
        }
    }

    public function openModal(DesignOrder $design_order, $emit_response_to)
    {
        $this->open = true;
        $this->emit_response_to = $emit_response_to;
        $this->design_order = $design_order;
    }

    public function store()
    {
        $this->validate();

        //storage optimized image
        $image_name = time() . Str::random(10) . '.' . $this->image->extension();
        $image_path = storage_path() . "/app/public/design-results/$image_name";
        Image::make($this->image)
            ->save($image_path, 40);

        $image_url = "public/design-results/$image_name";

        DesignResult::create([
            'image' => $image_url,
            'notes' => $this->notes,
            'design_order_id' => $this->design_order->id,
        ]);

        $this->design_order->update([
            'status' => 'Terminado',
        ]);

        $this->reset();

        $this->emit('success', 'Resultado subido');
        $this->emitTo($this->emit_response_to, 'load-design-result');
    }

    public function render()
    {
        return view('livewire.design-result.create-design-result');
    }
}
