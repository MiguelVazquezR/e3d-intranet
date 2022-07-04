<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatType extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'description'
    ];
}
