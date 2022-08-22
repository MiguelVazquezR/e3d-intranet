<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class QuoteProduct extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'id',
        'quote_id',
        'product_id',
        'quantity',
        'price',
        'show_image',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Returns no IVA total price
     * @return float
     * 
     * receive number of decimals to show
     * @param $decimal = 2, $formated = false
     */
    public function total($decimals = 2, $formated = false)
    {
        $total = floatval($this->quantity) * floatval($this->price);

        return $formated
            ? number_format($total, $decimals)
            : $total;
    }

    // optimizing upload images (media library package)
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(600)
            ->height(360);
    }
}
