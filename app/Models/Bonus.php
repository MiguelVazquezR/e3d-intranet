<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_time',
        'half_time',
        'name',
    ];

    public function amount( $hours_per_week )
    {
        if($hours_per_week >= 48) {
            return $this->attributes['full_time'];
        } else {
            return $this->attributes['half_time'];
        }
    }

}
