<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankData extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'beneficiary_name',
        'account',
        'CLABE',
        'bank',
        'supplier_id',
    ];

    public function supllier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
