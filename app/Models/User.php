<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    // relationships -------------------------------
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function activities()
    {
        return $this->hasMany(UserHasSellOrderedProduct::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function weekSalary($formated = true)
    {
        $week_salary = $this->employee->salary * $this->employee->hours_per_week;

        if ($formated)
            return number_format($week_salary, 2);
        else
            return $week_salary;
    }

    public function marketingTasks()
    {
        return $this->belongsToMany(MarketingTask::class)->withPivot('id', 'finished_at');
    }

    public function marketingProjects()
    {
        return $this->hasMany(MarketingProject::class);
    }

    /**
     * Returns array with all registers on current week.
     * PayRollRegister object if has checked_in or
     * String event 
     *
     * @return array
     */
    public function currentWeekRegisters($pay_roll = null)
    {
        if (is_null($pay_roll)) {
            $pay_roll = PayRoll::currentPayRoll();
            $pay_roll_id = $pay_roll['id'];
        } else {
            $pay_roll_id = $pay_roll;
        }
        $_current_week_registers = PayRollRegister::where('user_id', $this->id)
            ->where('pay_roll_id', $pay_roll_id)
            ->orderBy('day', 'asc')
            ->get();

        if (!$_current_week_registers->count()) {
            return [null, null, null, null, null, null, null];
        }
        return $this->_fillRegisters($_current_week_registers->all(), $pay_roll_id);
    }

    /**
     * fill info like time registered, justification or absent on current_week_registers array
     */
    protected function _fillRegisters($registers, $pay_roll_id)
    {
        $pay_roll = PayRoll::find($pay_roll_id);
        $current_week_registers = [];
        $week_registers_index = 0;
        foreach (Employee::WEEK as $index => $day) {
            if (array_key_exists($week_registers_index, $registers)) {
                //current day (index) is equal to current register day?
                if (PayRoll::currentDay($registers[$week_registers_index]->day->dayOfWeek) == $index) {
                    if ($registers[$week_registers_index]->justification_event_id) {
                        $current_week_registers[] = $registers[$week_registers_index]->justificationEvent->name;
                    } else {
                        $current_week_registers[] = $registers[$week_registers_index];
                    }
                    $week_registers_index++;
                } elseif (in_array($index, $this->employee->days_off)) {
                    $current_week_registers[] = 'Descanso';
                } elseif (in_array($pay_roll->start_period->addDays($index)->isoFormat('YYYY-MM-DD'), Holyday::arrayDates()) && !in_array($index, $this->employee->days_off)) {
                    $current_week_registers[] = 'Día feriado';
                } else {
                    $current_week_registers[] = 'Falta';
                }
            } /*elseif ( in_array( $pay_roll->start_period->addDays($index)->isoFormat('YYYY-MM-DD'), Holyday::arrayDates() ) && !in_array($index, $this->employee->days_off) )  {
                $current_week_registers[] = 'Día feriado';
            }*/ else { //days after today. No passed yet
                $current_week_registers[] = null;
            }
        }
        return $current_week_registers;
    }

    protected function _setTime($time, $new_register = false, $attribute = null)
    {
        if ($new_register) {
            $register = PayRollRegister::create([
                'pay_roll_id' => PayRoll::all()->last()->id,
                'user_id' => $this->id,
                'day' => date('Y-m-d'),
                'check_in' => $time,
            ]);
            $register->late();
        } else {
            $this->currentWeekRegisters()[PayRoll::currentDay()]->update([
                $attribute => $time
            ]);
        }
        return true;
    }

    public function nextPayRollRegister($time = null)
    {
        if (is_null($this->currentWeekRegisters()[PayRoll::currentDay()])) {
            if ($time) {
                return $this->_setTime($time, true);
            } else {
                return 'entrada';
            }
        } elseif (!$this->currentWeekRegisters()[PayRoll::currentDay()]->start_break) {
            if ($time) {
                return $this->_setTime($time, false, 'start_break');
            } else {
                return 'inicio break';
            }
        } elseif (!$this->currentWeekRegisters()[PayRoll::currentDay()]->end_break) {
            if ($time) {
                return $this->_setTime($time, false, 'end_break');
            } else {
                return 'fin break';
            }
        } elseif (!$this->currentWeekRegisters()[PayRoll::currentDay()]->check_out) {
            if ($time) {
                return $this->_setTime($time, false, 'check_out');
            } else {
                return 'salida';
            }
        } else {
            return 'Terminado';
        }
    }

    public function absences($pay_roll = null)
    {
        $absences = array_reduce($this->currentWeekRegisters($pay_roll), function ($carry, $item) {
            if (is_null($item) || $item == 'Falta') {
                $carry++;
            }
            return $carry;
        });

        return is_null($absences) ? 0 : $absences;
    }

    // public function countJustifications($pay_roll = null)
    // {
    //     $justifications = array_reduce($this->currentWeekRegisters($pay_roll), function ($carry, $item) {
    //         if ($item == 'Vacaciones' || $item == 'Falta justificada' || $item == 'Descanso' || $item == 'Incapacidad' || $item == 'Día feriado') {
    //             $carry++;
    //         }
    //         return $carry;
    //     });

    //     return is_null($justifications) ? 0 : $justifications;
    // }

    public function normalSalary($pay_roll = null, $formated = true)
    {
        $normal_salary = $this->employee->salary * $this->totalTime($pay_roll, false, true);

        if ($formated)
            return number_format($normal_salary, 2);
        else
            return $normal_salary;
    }

    public function extraSalary($pay_roll = null, $formated = true)
    {
        $extra_salary = $this->employee->salary * $this->totalExtraTime($pay_roll, false);

        if ($formated)
            return number_format($extra_salary, 2);
        else
            return $extra_salary;
    }

    public function totalTime($pay_roll = null, $in_text = true, $time_limitted = false)
    {
        $total_time = 0;
        if (is_null($pay_roll)) {
            $pay_roll = PayRoll::currentPayRoll()->id;
        }

        $total_time = array_reduce($this->currentWeekRegisters($pay_roll), function ($carry, $item) {
            $carry += $this->timeForRegister($item, false);
            return $carry;
        });

        if ($time_limitted) {
            $weekly_limit = $this->weeklyLimitTime($pay_roll, false);
            if ($total_time > $weekly_limit) {
                $total_time = $weekly_limit;
            }
        }

        if ($in_text)
            return $this->_hoursToTime($total_time);
        else
            return $total_time;
    }

    public function weeklyLimitTime($pay_roll_id = null, $in_text = true)
    {
        $weekly_limit = $this->employee->hours_per_week;
        if ($this->additionalTimeRequest($pay_roll_id)) {
            $weekly_limit += $this->additionalTimeRequest($pay_roll_id)->authorized_by
                ? $this->_timeToHours($this->additionalTimeRequest($pay_roll_id)->additional_time)
                : 0;
        }

        if ($in_text)
            return $this->_hoursToTime($weekly_limit);
        else
            return $weekly_limit;
    }

    public function totalExtraTime($pay_roll = null, $in_text = true)
    {
        $total_time = 0;
        if (is_null($pay_roll)) {
            $pay_roll = PayRoll::currentPayRoll()->id;
        }

        $total_time = array_reduce($this->currentWeekRegisters($pay_roll), function ($carry, $item) {
            $carry += $this->extraTime($item, false);
            return $carry;
        });

        if ($in_text)
            return $this->_hoursToTime($total_time);
        else
            return $total_time;
    }

    public function totalSalary($pay_roll = null, $formated = true)
    {
        $discount_by_late = $this->discountByDelay($pay_roll)
            ? $this->employee->salaryPerDay()
            : 0;

        $total_salary = $this->normalSalary($pay_roll, false) +
            array_sum($this->getBonuses($pay_roll)) -
            $this->employee->discounts -
            $discount_by_late +
            $this->extraSalary($pay_roll, false);

        if ($formated)
            return number_format($total_salary, 2);
        else
            return $total_salary;
    }

    public function timeForRegister($register, $in_text = true, $fill_empties = true)
    {
        $hours = 0;
        if (is_string($register)) {
            if ($register == 'Incapacidad') {
                $hours = $this->_hoursPerDay() * 0.60;
            } elseif ($register == 'Vacaciones') {
                $hours = $this->_hoursPerDay() * 1.25;
            } elseif ($register == 'Día feriado') {
                $hours = $this->_hoursPerDay();
            } else {
                $hours = 0;
            }
        } elseif (!is_null($register)) {
            if ($fill_empties) {
                //fill empty times for correct total time accumulated (only executed when pay roll isn't closed)
                $register = $register->fillEmptyTimes(false, false);
                //calculate total time
                $break_time = Carbon::parse($register->start_break)->floatDiffInHours($register->end_break);
                $hours = Carbon::parse($register->check_in)->floatDiffInHours($register->check_out) - $break_time;
                // substract 30 minutes to all roles except aulxiliar de producción
                // if (!$this->hasRole(['Auxiliar_producción', 'Empleado_remoto'])) $hours -= 0.33;
            } else {
                if ($register->check_out) {
                    //calculate total time
                    $break_time = Carbon::parse($register->start_break)->floatDiffInHours($register->end_break);
                    $hours = Carbon::parse($register->check_in)->floatDiffInHours($register->check_out) - $break_time;
                    // substract 30 minutes to all roles except aulxiliar de producción
                    // if (!$this->hasRole(['Auxiliar_producción', 'Empleado_remoto'])) $hours -= 0.33;
                } else {
                    $hours = 0;
                }
            }
        }

        if ($in_text)
            return $this->_hoursToTime($hours);
        else
            return $hours;
    }

    public function extraTime($register, $in_text = true)
    {
        if (!is_string($register) && !is_null($register)) {
            if (!$register->extras_enabled) return 0;
            else {
                $day = new Carbon($register->day);

                // if employee worked at rest day
                if (in_array(PayRoll::currentDay($day->dayOfWeekIso), $this->employee->days_off)) {
                    $extras = $this->timeForRegister($register, false);
                } else {
                    $extras = $this->timeForRegister($register, false) - $this->_hoursPerDay();
                }

                // negative extras?
                if ($extras < 0) {
                    $extras = 0;
                }
                if ($in_text)
                    return $this->_hoursToTime($extras);
                else
                    return $extras;
            }
        }
    }

    protected function add($carry, $item)
    {
        $carry += $this->timeForRegister($item, false);
        return $carry;
    }

    protected function _hoursPerDay()
    {
        return $this->employee->hours_per_week / (7 - count($this->employee->days_off));
    }

    protected function _hoursToTime($hours)
    {
        if ($hours == 0) return '--:--';

        $minutes = ($hours - intval($hours)) * 60;
        $minutes = intval(round($minutes));

        $hours = intval($hours);

        if ($hours < 10) $hours = "0$hours";

        if ($minutes < 10) $minutes = "0$minutes";

        return "$hours:$minutes";
    }

    protected function _timeToHours($time)
    {
        $hours = explode(':', $time)[0] + (explode(':', $time)[1] / 60);
        return $hours;
    }

    public function getBonuses($pay_roll = null)
    {
        $bonuses = [];
        $all_bonuses = Bonus::all();

        if (empty($this->employee->bonuses)) {
            foreach ($all_bonuses as $bonus) {
                $bonuses[$bonus->id] = 0;
            }
            return $bonuses;
        } else {
            foreach ($all_bonuses as $bonus) {
                $bonuses[$bonus->id] = $this->earnedByBonus($bonus, $pay_roll);
            }
            return $bonuses;
        }
    }

    public function earnedByBonus(Bonus $bonus, $pay_roll_id)
    {
        if (!is_null($pay_roll_id)) {
            $pay_roll = PayRoll::find($pay_roll_id);
        }

        $earned = 0;

        if (!in_array($bonus->id, $this->employee->bonuses)) return $earned;

        switch ($bonus->id) {
            case 1:
                $earned = $this->absences($pay_roll->id)
                    ? 0
                    : $bonus->amount($this->employee->hours_per_week);
                break;
            case 2:
            case 5:
                $earned = ($bonus->amount($this->employee->hours_per_week)/$this->_workeableDays()) * 
                ($this->_workeableDays() - count($this->lateDays($pay_roll->id)));
                // $earned = $this->lateDays($pay_roll->id)
                //     ? 0
                //     : $bonus->amount($this->employee->hours_per_week);
                break;
            case 3:
                $earned = $this->totalTime($pay_roll->id, false) < $this->employee->hours_per_week
                    ? 0
                    : $bonus->amount($this->employee->hours_per_week);
                break;
            case 4:
                $earned = ($this->currentWeekRegisters($pay_roll->id)[2] == 'Falta' || $this->currentWeekRegisters($pay_roll->id)[2] == null)
                    ? 0
                    : $bonus->amount($this->employee->hours_per_week);
                break;
                // case 6: {
                //         $total = ($this->_workeableDays() - $this->absences($pay_roll->id) /*- $this->countJustifications()*/) * $bonus->amount($this->employee->hours_per_week);
                //         $earned =
                //             $total >= 0
                //             ? $total
                //             : 0;
                //     }
                //     break;
        }

        return $earned;
    }

    public function _workeableDays()
    {
        return (7 - count($this->employee->days_off));
    }

    public function lateDays($week)
    {
        $late_days = [];
        foreach ($this->currentWeekRegisters($week) as $i => $register) {
            if ($register instanceof PayRollRegister) {
                if ($register->late) {
                    $late_days[] = $i;
                }
            }elseif(is_null($register)) {
                $late_days[] = $i;
            }elseif($register == 'Falta'){
                $late_days[] = $i;
            }
        }

        return $late_days;
    }

    public function getCommittedTime()
    {
        return $this->activities->sum('estimated_time');
    }

    public function additionalTimeRequest($pay_roll_id = null)
    {
        if ($pay_roll_id) {
            $request = PayRollMoreTime::where('user_id', $this->id)
                ->where('pay_roll_id', $pay_roll_id)
                ->get();
        } else {
            $request = PayRollMoreTime::where('user_id', $this->id)
                ->where('pay_roll_id', PayRoll::currentPayRoll()->id)
                ->get();
        }

        return $request->all()
            ? $request->all()[0]
            : null;
    }

    public function delayTime($pay_roll)
    {
        $delay_time = array_reduce($this->currentWeekRegisters($pay_roll), function ($carry, $item) {
            if (!is_null($item) && !is_string($item)) {
                // dd($item->late);
                $carry += $item->late;
            }
            return $carry;
        });

        return is_null($delay_time) ? 0 : $delay_time;
    }

    //config: limit of delay minutes per week
    public function discountByDelay($pay_roll_id = null)
    {
        $time_late = $this->delayTime($pay_roll_id);
        return (!$this->hasRole('Auxiliar_producción') &&  $time_late >= 15);
    }
}
