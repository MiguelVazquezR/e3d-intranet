<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'user_id',
        'order_name',
        'order_type',
        'tentative_end',
        'especifications',
        'status',
        'authorized_user_id',
        'authorized_at',
        'started_at',
    ];

    protected $dates = [
        'tentative_end',
        'authorized_at',
        'started_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function authorizedBy()
    {
        return $this->belongsTo(User::class, 'authorized_user_id');
    }

    public function results()
    {
        return $this->hasMany(MarketingOrderResult::class);
    }
}
