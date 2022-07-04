<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'location',
        'quantity',
        'image',
    ];

    // ---------------- relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // --------------- service methods

    /**
     * Determines if stock product needs reposition. 
     *
     * @return float
     */
    public function needsReposition()
    {
        return $this->quantity <= $this->product->min_stock;
    }

}
