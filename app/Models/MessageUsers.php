<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageUsers extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $dates = [
        'readed_at'
    ];

    // accesors &mutators
    public function markAsRead()
    {
        if(is_null($this->readed_at)) {
            $this->readed_at = date('Y-m-d H:i:s');
            $this->save();
        }
    }
    
    public function markAsUnread()
    {
        if(!is_null($this->readed_at)) {
            $this->readed_at = null;
            $this->save();
        }
    }

    // relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
