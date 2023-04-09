<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_number',
        'weight',
        'width',
        'large',
        'height',
        'cost',
        'supplier',
        'aquisition_date',
    ];

    protected $dates = [
        'aquisition_date'
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
