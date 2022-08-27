<?php

namespace App\Http\Livewire\DesignDepartment;

use App\Models\DesignOrder;
use App\Notifications\StartedOrderNotification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ShowDesignDepartment extends Component
{
    public $design_order,
        $open = false,
        $tentative_end,
        $design_results_list = [];

    protected $rules = [
        'tentative_end' => 'required'
    ];

    protected $listeners = [
        'openModal',
        'load-design-result' => 'loadDesignResults',
    ];

    public function mount()
    {
        $this->design_order = new DesignOrder();
    }

    public function openModal(DesignOrder $design_order)
    {
        $this->design_order = $design_order;
        $this->open = true;
        $this->design_results_list = $design_order->results;
    }

    public function loadDesignResults()
    {
        $this->design_results_list = $this->design_order->results;
    }

    public function addDesignResult()
    {
        $this->emitTo(
            'design-result.create-design-result',
            'openModal',
            $this->design_order,
            'design-department.show-design-department'
        );
    }

    public function deleteItem($index)
    {
        Storage::delete([$this->design_results_list[$index]->image]);
        $this->design_results_list[$index]->delete();
        unset($this->design_results_list[$index]);
    }

    public function storeTentativeEnd()
    {
        $this->validate();

        $this->design_order->update([
            'tentative_end' => $this->tentative_end,
            'status' => 'En proceso',
        ]);

        // notify to request's creator
        $this->design_order->creator->notify(new StartedOrderNotification('orden de diseño', $this->design_order->id, 'design-orders'));

        $this->resetExcept('design_order');

        $this->emitTo('design-department.home-design', 'render');
        $this->emit('success', 'Fecha tentativa de entrega establecida');
    }

    public function render()
    {
        return view('livewire.design-department.show-design-department');
    }
}
