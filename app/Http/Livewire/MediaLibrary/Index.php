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
        'open-folder' => 'openFolder',
    ];

    public function refresh()
    {
        $this->resources = E3dMedia::where('path', $this->current_path)
            ->orWhere(function($query) {
                $query->where('path', 'LIKE',  $this->current_path.'/%')
                    ->where('path', 'NOT LIKE',  $this->current_path.'/%\/%');
            })
            ->get();
    }
    
    public function back()
    {
        $splitted_path = collect(explode('/', $this->current_path));
        $splitted_path->pop();
        $splitted_path->implode('/');
        
        $this->current_path = $splitted_path->implode('/');

        $this->refresh();
    }

    public function openFolder($folder_name)
    {
        $this->current_path .= '/'.$folder_name; 
        $this->refresh();
    }

    public function openUploadModal()
    {
        $this->emitTo('media-library.upload', 'openModal', $this->current_path);
    }

    public function getCurrentPathProperty()
    {
        $splitted_current_path = collect(explode('/', $this->current_path));

        if($splitted_current_path->count() > 1) {
            $splitted_current_path->shift();
            return '/'.$splitted_current_path->implode('/');
        }
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
