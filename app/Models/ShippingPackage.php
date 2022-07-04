<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'width',
        'large',
        'height',
        'weight',
        'quantity',
        'status',
        'inside_image',
        'outside_image',
        'sell_ordered_product_id',
        'user_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sellOrderedProduct()
    {
        return $this->belongsTo(SellOrderedProduct::class);
    }
}
