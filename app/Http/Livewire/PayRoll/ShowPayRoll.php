<?php

namespace App\Http\Livewire\PayRoll;

use App\Models\PayRoll;
use Livewire\Component;

class ShowPayRoll extends Component
{
    public $pay_roll,
        $employees = [],
        $employees_selected = [],
        $open = false;

    protected $listeners = [
        'openModal',
    ];

    public function openModal(PayRoll $pay_roll)
    {
        $this->pay_roll = $pay_roll;
        foreach ($this->pay_roll->registers as $register) {
            $employee = $register->user;
            $this->employees[$employee->id] = $employee->name;
        }

        foreach ($this->employees as $id => $name) {
            $this->employees_selected[] = $id;
        }
    
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.pay-roll.show-pay-roll');
    }
}
