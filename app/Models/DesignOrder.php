<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer_id',
        'customer_id',
        'customer_name',
        'contact_id',
        'contact_name',
        'user_id',
        'design_name',
        'design_type_id',
        'tentative_end',
        'is_complex',
        'design_data',
        'measurement_unit_id',
        'especifications',
        'plans_image',
        'logo_image',
        'pantones',
        'dimentions',
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

    public function designer()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function designType()
    {
        return $this->belongsTo(DesignType::class);
    }

    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }

    public function authorizedBy()
    {
        return $this->belongsTo(User::class, 'authorized_user_id');
    }
    
    public function results()
    {
        return $this->hasMany(DesignResult::class);
    }

    public function isLate()
    {
        if ($this->results->count() && $this->started_at) {
            return $this->results->contains(function ($result) {
                $time_for_reuse = $this->limitTime() * ($this->reuse / 100);
                $limit_reached = $this->started_at->floatDiffInHours($result->created_at) > ($this->limitTime() * 1.10 - $time_for_reuse);
                $tentative_reached = $result->created_at->greaterThan($this->tentative_end);

                return $limit_reached || $tentative_reached;
            });
        }
        
        return false;
    }

    public function limitTime()
    {
        return $this->is_complex
        ? $this->designType->max_time
        : $this->designType->min_time;
    }

}
