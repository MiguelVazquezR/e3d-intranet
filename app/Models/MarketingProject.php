<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'objective',
        'project_owner_id',
        'authorized_by_id',
        'authorized_at',
        'feedback',
        'project_cost',
    ];

    protected $dates = [
        'authorized_at'
    ];

    // Relationships------------------------------
    public function tasks()
    {
        return $this->hasMany(MarketingTask::class);
    }

    public function results()
    {
        return $this->hasMany(MarketingResult::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'project_owner_id');
    }
    
    public function authorizedBy()
    {
        return $this->belongsTo(User::class);
    }
}
