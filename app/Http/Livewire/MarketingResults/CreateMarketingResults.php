<?php

namespace App\Http\Livewire\MarketingResults;

use App\Models\E3dMedia;
use App\Models\MarketingOrder;
use App\Models\MarketingOrderResult;
use App\Notifications\FinishedOrderNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMarketingResults extends Component
{
    use WithFileUploads;

    public $open = false,
        $external_link,
        $media_id,
        $result_in_library = 0,
        $marketing_order,
        $notes,
        $emit_response_to,
        // showing media library properties
        $current_path = 'ML-index',
        $images_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/bmp'],
        $resources = [];

    protected $rules = [
        'notes' => 'max:300',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];
    
    public function mount()
    {
        $this->refresh();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function openModal(MarketingOrder $marketing_order, $emit_response_to)
    {
        $this->open = true;
        $this->emit_response_to = $emit_response_to;
        $this->marketing_order = $marketing_order;
    }

    public function store()
    {
        if ($this->result_in_library) {
            $this->rules['media_id'] = 'required';
        } else {
            $this->rules['external_link'] = 'required';
        }

        $validated = $this->validate(null, [
            'media_id.required' => 'Debe seleccionar un archivo'
        ]);

        MarketingOrderResult::create($validated + [
            'marketing_order_id' => $this->marketing_order->id,
            'user_id' => auth()->id(),
        ]);

        $this->marketing_order->update([
            'status' => 'Terminado',
        ]);

        // notify to request's creator
        $this->marketing_order->creator->notify(new FinishedOrderNotification('mercadotecnia', $this->marketing_order->id, 'Se agregó un nuevo resultado', 'marketing-orders'));

        $this->reset();

        $this->emit('success', 'Resultado subido');
        $this->emitTo($this->emit_response_to, 'load-marketing-result');
        $this->emitTo('marketing.m-d-orders-index', 'render');
    }

    // showing media library methods
    public function refresh()
    {
        $this->resources = E3dMedia::where('path', $this->current_path)
            ->orWhere(function ($query) {
                $query->where('path', 'LIKE',  $this->current_path . '/%')
                    ->where('path', 'NOT LIKE',  $this->current_path . '/%\/%');
            })
            ->get();
    }

    public function back()
    {
        $splitted_path = collect(explode('/', $this->current_path));
        $splitted_path->pop();
        $splitted_path->implode('/');

        $this->current_path = $splitted_path->implode('/');

        $this->refresh();
    }

    public function openFolder($folder_name)
    {
        $this->current_path .= '/' . $folder_name;
        $this->refresh();
    }

    public function getCurrentPathProperty()
    {
        $splitted_current_path = collect(explode('/', $this->current_path));

        if ($splitted_current_path->count() > 1) {
            $splitted_current_path->shift();
            return '/' . $splitted_current_path->implode('/');
        }
    }

    public function render()
    {
        return view('livewire.marketing-results.create-marketing-results');
    }
}
