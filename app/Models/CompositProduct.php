<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompositProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'alias',
        'product_family_id',
        'product_status_id',
    ];

    public function family()
    {
        return $this->belongsTo(ProductFamily::class, 'product_family_id');
    }

    public function compositProductDetails()
    {
        return $this->hasMany(CompositProductDetails::class);
    }

    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }
    
}
