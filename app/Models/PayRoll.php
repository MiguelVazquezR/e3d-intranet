<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayRoll extends Model
{
    use HasFactory;

    protected $fillable = [
        'week',
        'start_period',
        'end_period',
        'closed',
    ];

    protected $dates = [
        'start_period',
        'end_period',
    ];

    public function registers()
    {
        return $this->hasMany(PayRollRegister::class);
    }

    // public function getStartPeriodAttribute()
    // {
    //     return new Carbon($this->attributes["start_period"]);
    // }
    
    // public function getEndPeriodAttribute()
    // {
    //     return new Carbon($this->attributes["end_period"]);
    // }

    public static function currentWeek()
    {
        if( self::currentDay() < 3 ) {
            $current_week = date('W') + 1;
        } else {
            $current_week = date('W');
        }

        return $current_week;
    }

    public static function currentDay( $day = null )
    {
        if( is_null($day) ) {
            $day = date('w');
        }

        if( $day == 5 ) {
            $current_day = 0;
        }elseif( $day == 6 ) {
            $current_day = 1;
        }else {
            $current_day = $day + 2;
        }

        return $current_day;
    }

    public static function currentPayRoll()
    {
        return PayRoll::all()->last();
    }

    public static function canClose()
    {
        $today_ends = self::currentPayRoll()->end_period->equalTo( Carbon::create(date('Y-m-d')) );

        return ( self::currentDay() == 6 && date('H') >= 20 && $today_ends );        
    }

    public function close()
    {
        $registers = PayRollRegister::whereHas('payRoll', function($query) {
            $query->where('week', PayRoll::currentWeek());
        })->get();

        foreach($registers as $register) {
            $register->fillEmptyTimes();
        }

        $this->closed = 1;
        $this->save();

        PayRoll::create([
            'start_period' => $this->start_period->addDays(7)->isoFormat('YYYY-MM-D'),
            'end_period' => $this->end_period->addDays(7)->isoFormat('YYYY-MM-D'),
            'week' => $this->start_period->addDays(10)->isoFormat('w') == 1 ? 1 : ($this->week + 1),
        ]);
    }
}
