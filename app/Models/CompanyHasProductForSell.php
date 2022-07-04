<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyHasProductForSell extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'model_name',
        'old_date',
        'new_date',
        'old_price',
        'new_price',
        'old_price_currency',
        'new_price_currency',
        'company_id',
    ];
    
    protected $dates = [
        'old_date',
        'new_date',
    ];

    // ---------------- relationships

    /**
     * Get the parent morpheable model (product or composite product).
     */
    // public function morpheable()
    // {
    //     return $this->morphTo();
    // }
    
    public function model()
    {
        if($this->model_name == Product::class)
        return $this->belongsTo(Product::class);
        else
        return $this->belongsTo(CompositProduct::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
