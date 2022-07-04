<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_company',
        'status',
        'priority',
        'freight_cost',
        'oce',
        'oce_name',
        'order_via',
        'invoice',
        'tracking_guide',
        'notes',
        'user_id',
        'contact_id',
        'customer_id',
        'received_at',
        'authorized_user_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function sellOrderedProducts()
    {
        return $this->hasMany(SellOrderedProduct::class);
    }

    public function authorizedBy()
    {
        return $this->belongsTo(User::class, 'authorized_user_id');
    }

    // helpers
    public function freightCurrency()
    {
        $cost_array = explode('$', $this->freight_cost);
        return count($cost_array) >= 2
            ? "$$cost_array[1]"
            : '';
    }
    public function freightCost()
    {
        $cost_array = explode('$', $this->freight_cost);
        return count($cost_array) >= 2
            ? "$cost_array[0]"
            : 0;
    }

    public function totallyPacked()
    {
        $all = SellOrderedProduct::where('sell_order_id', $this->id)
            ->get()
            ->count();
        $packaged = SellOrderedProduct::where('status', 'Empacado')
            ->where('sell_order_id', $this->id)
            ->get()
            ->count();

        return ($all == $packaged);
    }

    public function anyPacked()
    {
        $packaged = SellOrderedProduct::where('status', 'Empacado')
            ->where('sell_order_id', $this->id)
            ->get()
            ->count();

        return $packaged;
    }

    public function parciallyShipped()
    {
        foreach ($this->sellOrderedProducts as $sop) {
            foreach ($sop->shippingPackages as $package) {
                if ($package->status == 'Envio parcial')
                    return true;
            }
        }
        return false;
    }
}
