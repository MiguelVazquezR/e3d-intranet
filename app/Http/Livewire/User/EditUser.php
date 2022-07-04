<?php

namespace App\Http\Livewire\User;

use App\Models\Bonus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\MovementHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    public $open = false,
        $user,
        $employee,
        $days_off = [],
        $day_off_selected,
        $check_in_times = [],
        $check_in_time_selected,
        $check_in_time_index = 0,
        $bonuses = [],
        $bonus_selected,
        $roles_selected = [],
        $birth_date,
        $join_date,
        $same_check_in = 0;


    protected $rules = [
        'user.name' => 'required',
        'user.email' => 'max:191',
        'employee.salary' => 'required|numeric',
        'employee.discounts' => 'required|numeric',
        'employee.vacations' => 'required|numeric',
        'employee.hours_per_week' => 'required|numeric',
        'days_off' => 'required',
        'check_in_times' => 'required',
        'employee.job_position' => 'required',
        'employee.department_id' => 'required',
        'birth_date' => 'required',
        'join_date' => 'required',
    ];

    protected $add_check_in_time_rules = [
        'days_off' => 'required',
        'check_in_time_selected' => 'required',
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
                'same_check_in',
            ]);
        }
    }

    public function openModal(User $user)
    {
        $this->open = true;
        $this->user = $user;
        $this->roles_selected = $user->roles->pluck('id', 'name')->toArray();
        if ($user->employee) {
            $this->employee = $user->employee;
            $this->days_off = $user->employee->days_off;
            $this->bonuses = $user->employee->bonuses;
            $this->birth_date = $user->employee->birth_date->isoFormat('YYYY-MM-DD');
            $this->join_date = $user->employee->join_date->isoFormat('YYYY-MM-DD');
            if( count($user->employee->check_in_times) == 1 ) {
                $this->same_check_in = 1;
                $this->check_in_time_selected = $user->employee->check_in_times[0];
            } else {
                $this->check_in_times = $user->employee->getCheckInTimesWithDay();
            }
        }
    }

    public function updatedDayOffSelected()
    {
        if ($this->day_off_selected == '') return;

        if (!in_array($this->day_off_selected, $this->days_off)) {
            $this->days_off[] = $this->day_off_selected;
        }

        if ($this->day_off_selected == $this->check_in_time_index) {
            $this->check_in_time_index++;
        }
    }

    public function updatedBonusSelected()
    {
        if (!$this->bonus_selected) return;

        if (!in_array($this->bonus_selected, $this->bonuses)) {
            $this->bonuses[] = $this->bonus_selected;
        }
    }

    public function updatedSameCheckIn()
    {
        $this->reset([
            'check_in_times',
            'check_in_time_selected',
            'check_in_time_index',
        ]);
    }

    public function addCheckInTime()
    {
        $this->validate($this->add_check_in_time_rules, [
            'days_off.required' => 'Primero selecciones los días de descanso',
            'check_in_time_selected.required' => 'Coloque primero la hora de entrada',
        ]);

        if (!$this->same_check_in) {
            $this->check_in_times[Employee::WEEK[$this->check_in_time_index]] = $this->check_in_time_selected;
            do {
                $this->check_in_time_index++;
                if ($this->check_in_time_index == count(Employee::WEEK)) {
                    $this->check_in_time_index = 0;
                }
            } while (in_array($this->check_in_time_index, $this->days_off));
        }

        $this->reset('check_in_time_selected');
    }

    public function removeDayOff($index)
    {
        unset($this->days_off[$index]);
    }

    public function removeBonus($index)
    {
        unset($this->bonuses[$index]);
    }

    public function removeCheckInTime($index)
    {
        unset($this->check_in_times[$index]);
    }
    
    public function disable()
    {
        $this->user->active = 0;
        $this->user->save();

        $this->reset();

        $this->emitTo('user.users', 'render');
        $this->emit('success', 'Usuario dado de baja');
    }
    
    public function enable()
    {
        $this->user->active = 1;
        $this->user->save();

        $this->reset();

        $this->emitTo('user.users', 'render');
        $this->emit('success', 'Usuario dado de alta');
    }

    public function update()
    {
        if ($this->same_check_in) {
            $this->check_in_times[] = $this->check_in_time_selected;
        } else {
            // validate wether all days has check in time !!!
            $work_days = 7 - count($this->days_off);
            $this->rules['check_in_times'] = "array|min:$work_days";
        }

        $validated_data = $this->validate(null, [
            'check_in_times.min' => 'Indique las horas de entrada de todos los días laborales del empleado'
        ]);

        // delete all false roles (unchecked) to prevent sql error
        foreach ($this->roles_selected as $i => $role) {
            if (!$role) {
                unset($this->roles_selected[$i]);
            }
        }

        $this->user->save();    
        $this->user->syncRoles($this->roles_selected);

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó usuario con ID: {$this->user->id}"
        ]);
        
        $this->employee->days_off = implode('|', $validated_data['days_off']);
        $this->employee->check_in_times = implode('|', $validated_data['check_in_times']);
        $this->employee->bonuses = $this->bonuses ? implode('|', $this->bonuses) : null;
        $this->employee->birth_date = $this->birth_date;
        $this->employee->join_date = $this->join_date;
        
        $this->employee->save();    

        $this->reset();

        $this->emitTo('user.users', 'render');
        $this->emit('success', 'Usuario actualizado');
    }

    public function render()
    {
        return view('livewire.user.edit-user', [
            'departments' => Department::all(),
            'all_bonuses' => Bonus::all(),
            'roles' => Role::all()->pluck('id', 'name'),
        ]);
    }
}
