<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'authorized_user_id',
        'currency_id',
        'customer_id',
        'customer_name',
        'receiver',
        'department',
        'tooling_cost',
        'freight_cost',
        'first_production_days',
        'strikethrough_tooling_cost',
        'notes',
        'sell_order_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function sellOrder()
    {
        return $this->belongsTo(User::class);
    }

    public function authorized_by()
    {
        return $this->belongsTo(User::class, 'authorized_user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotedProducts()
    {
        return $this->hasMany(QuoteProduct::class, 'quote_id');
    }
    
    public function quotedCompositProducts()
    {
        return $this->hasMany(QuoteCompositProduct::class, 'quote_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    // Helpers -------------------------
    public function toolingCurrency()
    {
        $cost_array = explode('$',$this->tooling_cost);
        return count( $cost_array ) >= 2 
        ? "$$cost_array[1]"
        : '';
    }
    
    public function freightCurrency()
    {
        $cost_array = explode('$',$this->freight_cost);
        return count( $cost_array ) >= 2 
        ? "$$cost_array[1]"
        : '';
    }

    public function total($decimals = 2, $formated = false)
    {
        $total = 0;
        foreach ($this->quotedProducts as $product) {
            $total += $product->total();
        }
        foreach ($this->quotedCompositProducts as $product) {
            $total += $product->total();
        }

        return $formated
            ? number_format($total, $decimals)
            : $total;
    }
}
