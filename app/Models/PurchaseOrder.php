<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $dates = [
        'received_at',
        'expected_delivery_at',
        'emitted_at',
    ];

    protected $fillable = [
        'shipping_company',
        'tracking_guide',
        'status',
        'notes',
        'user_id',
        'contact_id',
        'supplier_id',
        'received_at',
        'authorized_user_id',
        'iva_included',
        'expected_delivery_at',
        'emitted_at',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function purchaseOrderedProducts()
    {
        return $this->hasMany(PurchaseOrderedProduct::class);
    }

    public function authorizedBy()
    {
        return $this->belongsTo(User::class, 'authorized_user_id');
    }

}
