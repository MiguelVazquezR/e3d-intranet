<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    // relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function pivot()
    {
        return $this->hasMany(MessageUsers::class);
    }

    public function notificationsExceptMine()
    {
        $collection = [];
        $notifications = DatabaseNotification::where('type', 'App\Notifications\MessageSent')
            ->where('notifiable_id', '<>', Auth::user()->id)
            ->get();

        foreach ($notifications as $notification) {
            if ($notification->data['id'] == $this->id) {
                $collection[] = $notification;
            }
        }

        return collect($collection);
    }

    public function MarkAsUnreadNotifications()
    {
        $notifications = $this->notificationsExceptMine();

        foreach ($notifications as $notification) {
            $notification->markAsUnread();
        }
    }
}
