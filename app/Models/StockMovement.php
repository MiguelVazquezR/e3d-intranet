<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'stock_product_id',
        'stock_action_type_id',
        'notes',
        'quantity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function action()
    {
        return $this->belongsTo(StockActionType::class, 'stock_action_type_id');
    }

    public function stockProduct()
    {
        return $this->belongsTo(StockProduct::class);
    }

}
