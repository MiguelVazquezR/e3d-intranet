<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'file',
        'marketing_task_user_id',
    ];

    // Relationships------------------------------
    // public function project()
    // {
    //     return $this->belongsTo(MarketingProject::class, 'marketing_project_id');
    // }
}
