<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'quantity',
        'price',
        'notes',
        'code',
        'product_id',
        'purchase_order_id',
    ];
    
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
