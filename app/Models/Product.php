<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'product_family_id',
        'product_status_id',
        'product_material_id',
        'measurement_unit_id',
        'min_stock',
    ];

    // ---------------- relationships
    public function family()
    {
        return $this->belongsTo(ProductFamily::class, 'product_family_id');
    }

    public function material()
    {
        return $this->belongsTo(ProductMaterial::class, 'product_material_id');
    }
    
    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }
}
