<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Holyday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'active',
    ];

    static public function arrayDates()
    {
        $dates = [];
        foreach ( self::all() as $holyday ) {
            if($holyday->active) {
                $dates[] = $holyday->date->isoFormat('YYYY-MM-DD');
            }
        }
        return $dates;
    }

    // accessors & mutatores
    public function getDateAttribute()
    {
        return new Carbon($this->attributes['date']);
    }

}
