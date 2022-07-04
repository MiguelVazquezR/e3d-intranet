<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'contactable_id',
        'contactable_type',
        'name',
        'email',
        'phone',
        'birth_date',
    ];

    protected $dates = [
        'birth_date'
    ];

    // ---------------- relationships

    /**
     * Get the parent contactable model (customer or supplier).
     */
    public function contactable()
    {
        return $this->morphTo();
    }

    // ---------------- service methods

    /**
     * Determines if contact has birthday in the range of days passed as parameter.
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
     * Calculate number of days left to contact's birthday. If birth_date attribute is null, 
     * it returns a big number.
     *
     * @return int
     */
    public function getDaysToBirthday()
    {
       return  $this->birth_date 
       ? ceil(Carbon::now()->floatDiffInDays(Carbon::parse(date('Y') . '-' . $this->birth_date->isoFormat('MM-DD')), false))
       : 1000;
    }
    


}
