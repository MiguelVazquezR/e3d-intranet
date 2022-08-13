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

    public function creator()
    {
        return $this->belongsTo(User::class, 'project_owner_id');
    }
    
    public function authorizedBy()
    {
        return $this->belongsTo(User::class);
    }

    // methods -------------------------------------------------------
    public function uncompletedTasks()
    {
        $uncompleted = collect();
        foreach($this->tasks as $task) {
            if(!$task->isCompleted()) {
                $uncompleted->push($task);
            }
        }

        return $uncompleted;
    }

    public function completedTasks()
    {
        $completed = collect();
        foreach($this->tasks as $task) {
            if($task->isCompleted()) {
                $completed->push($task);
            }
        }

        return $completed;
    }

    public function progressPercentage()
    {
        $percentage = ($this->completedTasks()->count() / $this->tasks->count()) * 100;
        return round($percentage, 2);
    }
}
