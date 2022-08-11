<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayRollRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'check_in',
        'start_break',
        'end_break',
        'check_out',
        'pay_roll_id',
        'justification_event_id',
        'late',
        'user_id',
    ];

    protected $dates = [
        'day'
    ];

    public function payRoll()
    {
        return $this->belongsTo(PayRoll::class);
    }

    public function justificationEvent()
    {
        return $this->belongsTo(JustificationEvent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function getDayAttribute()
    // {
    //     return new Carbon($this->attributes["day"]);
    // }

    public function late()
    {
        if (count($this->user->employee->checkInTimes) > 1) {
            $check_in = $this->user
                ->employee
                ->checkInTimes[PayRoll::currentDay()] ?? '07:00';
        } else {
            $check_in = $this->user
                ->employee
                ->checkInTimes[0];
        }

        $checkin_time = Carbon::parse($check_in);
        $attendence_time = Carbon::parse($this->check_in);
        
        $minutes_late = $checkin_time->diffInMinutes($attendence_time);

        if ($minutes_late > 0) {
            $this->late = $minutes_late;
        } else {
            $this->late = 0;
        }

        $this->save();
        return $minutes_late;
    }

    public function fillEmptyTimes($update_record = true, $previus_register = true)
    {
        if (!$this->start_break) {
            if ($previus_register) {
                $this->start_break = $this->check_in;
                $this->end_break = $this->check_in;
                $this->check_out = $this->check_in;
            } else {
                $this->start_break = date('H:i');
                $this->end_break = date('H:i');
                $this->check_out = date('H:i');
            }
        } elseif (!$this->end_break) {
            if ($previus_register) {
                $this->end_break = $this->start_break;
                $this->check_out = $this->start_break;
            } else {
                $this->end_break = date('H:i');
                $this->check_out = date('H:i');
            }
        } elseif (!$this->check_out) {
            if ($previus_register) {
                $this->check_out = $this->end_break;
            } else {
                $this->check_out = date('H:i');
            }
        }
        if ($update_record) {
            $this->save();
        } else {
            return $this;
        }
    }
}
