<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendence',
        'user_id',
        'meeting_id',
        'comments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

}
