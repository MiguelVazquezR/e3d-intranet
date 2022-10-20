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
        'deleteFile',
        'deleteFolder',
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

    public function deleteFile(array $data)
    {
        // [E3dMedia Object, media id, Bool delete folder (delete E3dMedia object)]
        $e3d_media = E3dMedia::find($data[0]['id']);
        $e3d_media->deleteMedia($data[1]);

        if(!($e3d_media->getMedia($e3d_media->path)->count() - 1) && $data[2])
            $e3d_media->delete();

        $this->emitTo('media-library.index', 'refresh-resources');
        $this->emit('success', "Removido exitosamente");
    }
    
    public function deleteFolder($folder)
    {
        $path = $this->current_path.'/'.$folder;
        $resources_to_delete = E3dMedia::where('path', 'LIKE',  $path.'%')
        ->get();

        $resources_to_delete->each(fn ($resource) => $resource->getMedia($resource->path)
        ->each(fn ($media) => $this->deleteFile([$resource, $media->id, true])));
        
        $this->emitTo('media-library.index', 'refresh-resources');
        $this->emit('success', "Removido exitosamente");
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
