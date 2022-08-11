<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'finished_at',
        'estimated_finish',
        'marketing_project_id',
    ];

    protected $dates = [
        'finished_at',
        'estimated_finish',
    ];

    // Relationships------------------------------
    public function project()
    {
        return $this->belongsTo(MarketingProject::class, 'marketing_project_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('finished_at');
    }
}
