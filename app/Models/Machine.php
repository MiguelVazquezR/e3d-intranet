<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Machine extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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

    //relationships
        public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

        public function spareParts()
    {
        return $this->hasMany(SparePart::class);
    }
}
