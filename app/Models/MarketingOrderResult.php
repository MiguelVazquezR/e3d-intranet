<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MarketingOrderResult extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'external_link',
        'user_id',
        'marketing_order_id',
        'media_id',
        'notes',
    ];

    public function marketingOrder()
    {
        return $this->belongsTo(MarketingOrder::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
