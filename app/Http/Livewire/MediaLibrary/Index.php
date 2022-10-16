<?php

namespace App\Http\Livewire\MediaLibrary;

use App\Models\E3dMedia;
use Livewire\Component;

class Index extends Component
{
    public $current_path = 'ML-index',
        $resources = [];

    protected $listeners = [
        'refresh-resources' => 'refresh',
        'change-current-path' => 'changeCurrentPath',
    ];

    public function refresh()
    {
        $this->resources = E3dMedia::where('path', $this->current_path)
            ->orWhere('path', 'LIKE',  $this->current_path.'/%')
            ->get();
            // dd($this->resources->first()->getMedia($this->current_path));
    }
    
    public function back()
    {
        $splitted_path = collect(explode('/', $this->current_path));
        $splitted_path->pop();
        $splitted_path->implode('/');
        
        $this->current_path = $splitted_path->implode('/');

        $this->resources = E3dMedia::where('path', $this->current_path)
            ->orWhere('path', 'LIKE',  $this->current_path.'/%')
            ->get();
    }

    public function changeCurrentPath($folder)
    {
        $this->current_path .= '/'.$folder; 
        $this->refresh();
        dd($this->current_path);
    }

    public function mount()
    {
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.media-library.index');
    }
}
