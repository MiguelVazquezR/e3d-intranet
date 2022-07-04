<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'address',
        'post_code',
        'company_id',
    ];

    /**
     * Get all of the supplier's contacts.
     */
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankData::class);
    }
}
