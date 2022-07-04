<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'bussiness_name',
        'phone',
        'rfc',
        'fiscal_address',
        'post_code',
    ];

    // ---------------- relationships

    public function productsForSell()
    {
        return $this->hasMany(CompanyHasProductForSell::class);
    }
    
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }


}
