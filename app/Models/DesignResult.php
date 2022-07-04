<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'design_order_id',
        'image',
        'notes',
    ];

    public function designOrder()
    {
        return $this->belongsTo(DesignOrder::class);
    }

}
