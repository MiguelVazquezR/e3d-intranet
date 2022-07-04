<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'address',
        'post_code',
        'sat_method_id',
        'sat_type_id',
        'sat_way_id',
        'company_id',
    ];


    // relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function satMethod()
    {
        return $this->belongsTo(SatMethod::class);
    }

    public function satWay()
    {
        return $this->belongsTo(SatWay::class);
    }

    public function satType()
    {
        return $this->belongsTo(SatType::class);
    }

    /**
     * Get all of the customer's contacts.
     */
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    
}
