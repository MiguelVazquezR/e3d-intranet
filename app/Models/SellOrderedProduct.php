<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellOrderedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'quantity',
        'for_sell',
        'new_design',
        'notes',
        'status',
        'company_has_product_for_sell_id',
        'sell_order_id',
    ];

    // relationships -----------------------------------
    public function activityDetails()
    {
        return $this->hasMany(UserHasSellOrderedProduct::class);
    }
    
    public function sellOrder()
    {
        return $this->belongsTo(SellOrder::class);
    }

    public function productForSell()
    {
        return $this->belongsTo(CompanyHasProductForSell::class, 'company_has_product_for_sell_id');
    }

    public function shippingPackages()
    {
        return $this->hasMany(ShippingPackage::class);
    }

    // others- ---------------------------------------------
    public function getEstimatedTime()
    {
        return 90;
    }
}
