<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $dates = [
        'start',
        'end',
    ];

    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'location',
        'url',
        'status',
        'user_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function changeStatus()
    {
        $meeting_passed = $this->end->lessThan( Carbon::now() );
        $meeting_initied = $this->start->lessThan( Carbon::now() );

        if ($this->status == 'Esperando inicio' && $meeting_initied && !$meeting_passed) {
            $this->status = 'Iniciada';
            $this->save();
        } elseif ($this->status == 'Iniciada' && $meeting_passed) {
            $this->status = 'Terminada';
            $this->save();
        } elseif ($this->status == 'Esperando inicio' && $meeting_passed) {
            $this->status = 'Terminada';
            $this->save();
        }
    }

}
