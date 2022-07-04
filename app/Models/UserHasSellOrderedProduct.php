<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasSellOrderedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'estimated_time',
        'time_paused',
        'indications',
        'sell_ordered_product_id',
        'user_id',
        'start',
        'pause',
        'finish',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sellOrderedProduct()
    {
        return $this->belongsTo(SellOrderedProduct::class);
    }

    public function status()
    {
        if ($this->pause) {
            $status = 'Pausado';
        } elseif ($this->finish) {
            $status = 'Terminado';
        } elseif ($this->start) {
            $status = 'En proceso';
        } else {
            $status = 'Sin iniciar';
        }
        return $status;
    }

    public function totalTime()
    {
        $start = new Carbon($this->attributes["start"]);
        $finish = new Carbon($this->attributes["finish"]);
        $start_to_finish_time = $start->diffInMinutes($finish);

        return $start_to_finish_time - $this->time_paused;
    }

}
