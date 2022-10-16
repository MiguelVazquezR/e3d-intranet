<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class E3dMedia extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['user_id', 'num_files', 'path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // methods
    public function nextFolder($current_path)
    {
        $splitted_path = explode('/', $this->path);
        return end($splitted_path) !== $current_path 
            ? end($splitted_path)
            : null;
    }
}
