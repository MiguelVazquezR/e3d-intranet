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

class CreateUser extends Component
{
    public $open = false,
        $name,
        $email,
        $password,
        $salary,
        $discounts,
        $hours_per_week,
        $days_off = [],
        $day_off_selected,
        $check_in_times = [],
        $check_in_time_selected,
        $check_in_time_index = 0,
        $bonuses = [],
        $bonus_selected,
        $job_position,
        $department_id,
        $birth_date,
        $join_date,
        $roles_selected = [],
        $same_check_in = 0;


    protected $rules = [
        'name' => 'required',
        'salary' => 'required|numeric',
        'discounts' => 'required|numeric',
        'hours_per_week' => 'required|numeric',
        'days_off' => 'required',
        'check_in_times' => 'required',
        'job_position' => 'required',
        'department_id' => 'required',
        'birth_date' => 'required',
        'join_date' => 'required',
    ];

    protected $add_check_in_time_rules = [
        'days_off' => 'required',
        'check_in_time_selected' => 'required',
    ];

    protected $listeners = [
        'render',
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

    public function updatedName()
    {
        $this->password = "e3d" . mb_strtoupper(mb_substr($this->name, 0, 3));
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

    public function store()
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

        $validated_data['days_off'] = implode('|', $validated_data['days_off']);
        $validated_data['check_in_times'] = implode('|', $validated_data['check_in_times']);

        $user_data = [
            'name' => $this->name,
            'email' => strtolower($this->email),
            'password' => bcrypt($this->password),
        ];

        $employee_data = array_slice($validated_data, 1);

        $user = User::create($user_data);

        $user->syncRoles($this->roles_selected);

        $employee_data += [
            'bonuses' => $this->bonuses ? implode('|', $this->bonuses) : null,
            'user_id' => $user->id,
            'vacations_updated_at' => date('Y-m-d'),
        ];

        Employee::create($employee_data);

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se creó nuevo usuario de nombre: {$user->name}"
        ]);

        $this->reset();

        $this->emitTo('user.users', 'render');
        $this->emit('success', 'Nuevo usuario');
    }

    public function render()
    {
        return view('livewire.user.create-user', [
            'departments' => Department::all(),
            'all_bonuses' => Bonus::all(),
            'roles' => Role::all()->pluck('id', 'name'),
        ]);
    }
}
