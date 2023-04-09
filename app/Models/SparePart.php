<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'supplier',
        'cost',
        'description',
        'location',
        'machine_id'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}


