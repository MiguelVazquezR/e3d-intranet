<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bussiness_name',
        'logo',
        'shield',
        'address',
        'post_code',
        'rfc',
        'phone1',
        'phone2',
        'web_site',
    ];

}
