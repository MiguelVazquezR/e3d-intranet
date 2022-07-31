<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'remind_at',
        'user_id',
    ];

    protected $dates = [
        'remind_at'
    ];

    // Relationships --------------------
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
