<?php

namespace App\Http\Livewire\PayRollRegister;

use App\Models\Employee;
use App\Models\JustificationEvent;
use App\Models\MovementHistory;
use App\Models\PayRoll;
use App\Models\PayRollRegister;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreatePayRollRegister extends Component
{
    public $open = false,
        $user,
        $current_week_registers = [];

    protected $rules = [
        'current_week_registers.*.check_in' => 'max:12',
        'user' => 'required',
    ];

    protected $listeners = [
        'render',
        'justification',
        'registerTime',
        'close' => 'closePayRoll',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function updatedUser()
    {
        $this->user = User::find($this->user);
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function markLate($index)
    {
        PayRollRegister::find($this->current_week_registers[$index]["id"])->update([
            'late' => 1
        ]);

        $this->emit('success', "Se ha marcado retardo");
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function extrasToDouble($index)
    {
        $register = PayRollRegister::find($this->current_week_registers[$index]["id"]);

        // $week_day = new Carbon($register->day);
        $week_day = Employee::WEEK[$index];

        if ($register->extras_enabled) {
            $register->extras_enabled = 0;
        } else {
            $register->extras_enabled = 1;
        }

        $register->save();

        $this->emit('success', "Se han activado las horas extras como dobles para el día $week_day");
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function unmarkLate($index)
    {
        PayRollRegister::find($this->current_week_registers[$index]["id"])->update([
            'late' => 0
        ]);

        $this->emit('success', "Se ha retirado el retardo");
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function removeAbsence($index)
    {
        $register = PayRollRegister::create([
            'pay_roll_id' => PayRoll::currentPayRoll()->id,
            'user_id' => $this->user->id,
            'day' => PayRoll::currentPayRoll()->start_period->addDays($index),
            'check_in' => date('H:i')
        ]);

        $this->emit('success', "Se ha quitado la falta");
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function removeJustification($index)
    {
        $message = "Se ha quitado la justificación.";

        $register = PayRollRegister::where('day', PayRoll::currentPayRoll()->start_period->addDays($index))
            ->where('user_id', $this->user->id)
            ->first();

        if ($register->justification_event_id == 1) {
            $this->user->employee->vacations += 1;
            $this->user->employee->save();
            $message = "Se ha quitado la justificación de la falta y se regresó el día de vacaciones al usuario.";
        }

        $register->update([
            'justification_event_id' => null
        ]);

        $this->emit('success', $message);
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function justification($data)
    {
        //data[0] = day of week
        //data[1] = justification event id
        $justification_event = JustificationEvent::find($data[1]);
        $register = PayRollRegister::where('day', PayRoll::currentPayRoll()->start_period->addDays($data[0]))
            ->where('user_id', $this->user->id)
            ->get();

        if ($register->count()) {
            if ($justification_event->id == 1) {
                if ($this->user->employee->vacations >= 1) {
                    $this->user->employee->vacations -= 1;
                    $this->user->employee->save();
                } else {
                    $this->emit('error', "No tiene vacaciones");
                    $this->current_week_registers = $this->user->currentWeekRegisters();
                    return;
                }
            }
            $register[0]->update([
                'justification_event_id' => $justification_event->id
            ]);
        } else {
            if ($justification_event->id == 1) {
                if ($this->user->employee->vacations >= 1) {
                    $this->user->employee->vacations -= 1;
                    $this->user->employee->save();
                } else {
                    $this->emit('error', "No tiene vacaciones");
                    $this->current_week_registers = $this->user->currentWeekRegisters();
                    return;
                }
            }
            $register = PayRollRegister::create([
                'pay_roll_id' => PayRoll::currentPayRoll()->id,
                'user_id' => $this->user->id,
                'day' => PayRoll::currentPayRoll()->start_period->addDays($data[0]),
                'check_in' => date('H:i'),
                'justification_event_id' => $justification_event->id,
            ]);
        }

        $this->emit('success', "Se ha marcado como $justification_event->name");
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function verifyLocation()
    {
        if (Auth::user()->can('home_office')) {
            $this->registerTime();
        } else {
            $this->emit('getLocation');
        }
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function registerTime()
    {
        $now = date('H:i');
        $this->user->nextPayRollRegister($now);

        $this->user->employee->updateVacations();
        $this->emit('success', "Se ha registrado a las $now");
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function update()
    {
        foreach ($this->current_week_registers as $i => $register) {
            if (is_array($register)) {
                if (array_key_exists('id', $register)) {
                    if (!$register['check_in']) {
                        PayRollRegister::find($register["id"])->delete();
                    } else {
                        unset($register["day"]);
                        $obj_register = PayRollRegister::find($register["id"]);
                        if ($obj_register->check_in) {
                            $obj_register->update($register);
                            $obj_register->late();
                        } else {
                            $obj_register->delete();
                        }
                    }
                } else {
                    $obj_register = PayRollRegister::create([
                        'pay_roll_id' => PayRoll::currentPayRoll()->id,
                        'user_id' => $this->user->id,
                        'day' => PayRoll::currentPayRoll()->start_period->addDays($i),
                        'check_in' => date('H:i')
                    ]);
                    $obj_register->update($register);
                    $obj_register->late();
                }
            }
        }

        $this->emit('success', "Se han guardado los cambios");
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function closePayRoll()
    {
        $pay_roll = PayRoll::currentPayRoll();
        $pay_roll->close();

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se cerró nómina de la semana {$pay_roll->week}"
        ]);

        $this->emit('success', 'Nómina cerrada. Cualquier cambio se puede realizar en la sección de edición');
        $this->emitTo('pay-roll.pay-rolls', 'render');
        $this->reset();
    }

    public function requestTime()
    {
        $this->emitTo('pay-roll-more-time.create', 'openModal');
        $this->current_week_registers = $this->user->currentWeekRegisters();
    }

    public function render()
    {
        if (Auth::user()->can('ver_todas_las_nóminas')) {
            $users = User::role('Empleado')
                ->where('active', 1)
                ->get();
        } else {
            $this->user = Auth::user();
            $this->current_week_registers = $this->user->currentWeekRegisters();
            $users = [];
        }


        return view('livewire.pay-roll-register.create-pay-roll-register', [
            'users' => $users,
            'justification_events' => JustificationEvent::all(),
        ]);
    }
}
