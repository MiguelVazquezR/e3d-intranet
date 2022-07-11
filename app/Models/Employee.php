<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\TabCompletion\Matcher\FunctionsMatcher;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary',
        'hours_per_week',
        'discounts',
        'bonuses',
        'days_off',
        'check_in_times',
        'job_position',
        'department_id',
        'user_id',
        'birth_date',
        'join_date',
        'vacations',
        'vacations_updated_at',
    ];

    protected $dates = [
        'birth_date',
        'join_date',
        'vacations_updated_at'
    ];

    const WEEK = [
        'Viernes',
        'Sábado',
        'Domingo',
        'Lunes',
        'Martes',
        'Miércoles',
        'Jueves',
    ];

    // Relationships -----------------------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // accessors & mutatores -----------------------------
    public function getBonusesAttribute()
    {
        return $this->attributes['bonuses']
            ? explode('|', $this->attributes['bonuses'])
            : [];
    }

    public function getDaysOffAttribute()
    {
        return $this->attributes['days_off']
            ? explode('|', $this->attributes['days_off'])
            : [];
    }

    public function getCheckInTimesAttribute()
    {
        return $this->attributes['check_in_times']
            ? explode('|', $this->attributes['check_in_times'])
            : [];
    }

    public function getCheckInTimesWithDay()
    {
        if (count($this->check_in_times) > 1) {
            return $this->_addDay();
        } else {
            return [$this->check_in_times[0] . ' Todos sus días laborales'];
        }
    }

    // service methods

    /**
     * Determines if employee has birthday in the range of days passed as parameter.
     *
     * @param  int  $days_left
     * @return bool
     */
    public function HasBirthdaySoon($days_left = 3)
    {
        $days_to_birthday = $this->getDaysToBirthday();
        return ( $days_to_birthday >= 0 && $days_to_birthday <= $days_left);
    }
    
   /**
     * Determines if employee has anniversary in the range of days passed as parameter.
     *
     * @param  int  $days_left
     * @return bool
     */
    public function HasAnniversarySoon($days_left = 0)
    {
        $days_to_anniversary = $this->getDaysToanniversary();
        return ( $days_to_anniversary >= 0 && $days_to_anniversary <= $days_left);
    }

     /**
     * Calculate number of days left to employee's birthday. 
     *
     * @return int
     */
    public function getDaysToBirthday()
    {
       return ceil(Carbon::now()->floatDiffInDays(Carbon::parse(date('Y') . '-' . $this->birth_date->isoFormat('MM-DD')), false));
    }
    
     /**
     * Calculate number of days left to employee's anniversary. 
     *
     * @return int
     */
    public function getDaysToAnniversary()
    {
       return ceil(Carbon::now()->floatDiffInDays(Carbon::parse(date('Y') . '-' . $this->join_date->isoFormat('MM-DD')), false));
    }

    /**
     * Calculate number of years since employee joined to organization. 
     *
     * @return float
     */
    public function joinedYears()
    {
        return $this->join_date->floatDiffInYears();
    }
    
    /**
     * Calculate number of days since employee joined to organization. 
     *
     * @return float
     */
    public function joinedDays()
    {
        return $this->join_date->floatDiffInDays();
    }
    
    /**
     * Return the operator with least time committed . 
     *
     * @return App\Modals\User
     */
    public static function getAvailableOperator()
    {
        $operators = User::role('Auxiliar_producción')
        ->where('active', 1)
        ->with('employee')
        ->get();

        $available = null;

        foreach ($operators as $operator) {
            if(is_null($available)) {
                $available = $operator;
            } else {
                if ($available->getCommittedTime() > $operator->getCommittedTime()) {
                    $available = $operator;
                }
            }
        }

        return $available;
    }

    protected function _addDay()
    {
        $new_array = array();
        $check_in_times_index = 0;

        foreach (self::WEEK as $i => $day) {
            if (!in_array($i, $this->days_off)) {
                $new_array["$day"] = $this->check_in_times[$check_in_times_index];
                $check_in_times_index++;
            }
        }

        return $new_array;
    }

    public function updateVacations()
    {
        //check if has passed 1 week since last updated
        if ($this->vacations_updated_at->diffInDays() >= 7) {
            //update vacations
            if ($this->join_date->diffInYears() == 0) {
                $this->vacations = $this->vacations + 0.116;
            } elseif ($this->join_date->diffInYears() == 1) {
                $this->vacations = $this->vacations + 0.154;
            } elseif ($this->join_date->diffInYears() == 2) {
                $this->vacations = $this->vacations + 0.193;
            } elseif ($this->join_date->diffInYears() == 3) {
                $this->vacations = $this->vacations + 0.231;
            } elseif ($this->join_date->diffInYears() >= 4 && $this->join_date->diffInYears() < 9) {
                $this->vacations = $this->vacations + 0.27;
            } elseif ($this->join_date->diffInYears() >= 9 && $this->join_date->diffInYears() < 14) {
                $this->vacations = $this->vacations + 0.308;
            } elseif ($this->join_date->diffInYears() >= 14 && $this->join_date->diffInYears() < 19) {
                $this->vacations = $this->vacations + 0.347;
            } elseif ($this->join_date->diffInYears() >= 19 && $this->join_date->diffInYears() < 24) {
                $this->vacations = $this->vacations + 0.385;
            } elseif ($this->join_date->diffInYears() >= 24 && $this->join_date->diffInYears() < 29) {
                $this->vacations = $this->vacations + 0.424;
            } elseif ($this->join_date->diffInYears() >= 29) {
                $this->vacations = $this->vacations + 0.462;
            }
            $this->vacations_updated_at = $this->vacations_updated_at->addDays(7)->isoFormat('YYYY-MM-DD');
            $this->save();
        }
    }

    public function currentVacationPeriod()
    {
        $current_year = Carbon::parse(date('Y') . '-' . $this->join_date->isoFormat('MM-DD'));
        $end = $current_year->isoFormat('DD/MMMM/YYYY');
        $start = $current_year->subYear()->isoFormat('DD/MMMM/YYYY');

        $period = [
            'start' => $start,
            'end' => $end,
        ];

        return $period;
    }

    public function hoursPerDay()
    {
        return $this->hours_per_week / (7 - count($this->days_off));
    }

    public function vacationsPay($formated = true)
    {
        $vacation_bonus = $this->vacations * $this->salary * $this->hoursPerDay();

        if ($formated)
            return number_format($vacation_bonus, 2);
        else
            return $vacation_bonus;
    }

    public function vacationsBonus($formated = true)
    {
        $vacation_bonus = $this->vacationsPay(false) * 0.25;

        if ($formated)
            return number_format($vacation_bonus, 2);
        else
            return $vacation_bonus;
    }

    public function totalVacationsPay($formated = true)
    {
        $total = $this->vacationsPay(false) + $this->vacationsBonus(false);

        if ($formated)
            return number_format($total, 2);
        else
            return $total;
    }

    public function vacationsTaken()
    {
        $current_year = Carbon::parse(date('Y') . '-' . $this->join_date->isoFormat('MM-DD'));
        $to = $current_year->isoFormat('YYYY-MM-DD');
        $from = $current_year->subYear()->isoFormat('YYYY-MM-DD');
        $vacations_taken = PayRollRegister::where('user_id', $this->user->id)
            ->where('justification_event_id', 1)
            ->where('day', '>=', $from)
            ->where('day', '<=', $to)
            ->get();

        return $vacations_taken;
    }
}
