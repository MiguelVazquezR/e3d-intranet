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

    public function getTentativeEndAttribute()
    {
        return $this->attributes['tentative_end']
        ? new Carbon($this->attributes['tentative_end'])
        : null;
    }

    public function getAuthorizedAtAttribute()
    {
        return new Carbon($this->attributes['authorized_at']);
    }
}
