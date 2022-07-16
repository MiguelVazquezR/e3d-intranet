<?php

namespace App\Http\Livewire\PayRollMoreTime;

use App\Models\PayRollMoreTime;
use Livewire\Component;

class Show extends Component
{
    public $open = false,
        $request;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->request = new PayRollMoreTime();
    }

    public function openModal(PayRollMoreTime $request)
    {
        $this->request = $request;
        $this->open = true;
    }

    public function authorize()
    {
        $this->request->authorized_by = auth()->user()->id;
        $this->request->authorized_at = now();
        $this->request->save();

        $this->emit('success', 'Se ha autorizado la solicitud');
        $this->emitTo('pay-roll-more-time.index', 'render');
    }
    
    public function removeAuthorization()
    {
        $this->request->authorized_by = null;
        $this->request->authorized_at = null;
        $this->request->save();
        
        $this->emit('success', 'Se ha retirado la autorización de la solicitud');
        $this->emitTo('pay-roll-more-time.index', 'render');
    }

    public function render()
    {
        return view('livewire.pay-roll-more-time.show');
    }
}
