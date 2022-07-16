<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayRollMoreTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'pay_roll_id',
        'user_id',
        'report',
        'authorizad_by',
        'authorized_at',
        'additional_time',
        'comments',
    ];

    protected $dates = [
        'authorized_at'
    ];

    // Relationships ------------------------
    public function payRoll()
    {
        return $this->belongsTo(PayRoll::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
