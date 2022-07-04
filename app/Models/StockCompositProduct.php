<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCompositProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'composit_product_id',
        'location',
        'quantity',
        'image',
    ];

    public function compositProduct()
    {
        return $this->belongsTo(CompositProduct::class);
    }
}
