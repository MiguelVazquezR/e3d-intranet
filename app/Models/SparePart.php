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
<<<<<<< HEAD
        'machine_id',
    ];

    //relationships
}
=======
        'location',
        'machine_id'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}


>>>>>>> fe7dea19d8ecf06d2cd0ef4f68995271152228c9
