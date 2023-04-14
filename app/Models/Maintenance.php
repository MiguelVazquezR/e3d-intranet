<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'problems',
        'actions',
        'cost',
        'maintenance_type',
        'responsible',
        'machine_id',
    ];

    //relationships
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
