<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockActionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'movement',
    ];
}
