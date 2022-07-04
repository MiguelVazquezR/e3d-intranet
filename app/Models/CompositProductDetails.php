<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositProductDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'notes',
        'product_id',
        'composit_product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
