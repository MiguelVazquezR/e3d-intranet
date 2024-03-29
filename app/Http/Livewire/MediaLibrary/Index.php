<?php

namespace App\Http\Livewire\MediaLibrary;

use App\Models\E3dMedia;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Index extends Component
{
    public $current_path = 'ML-index',
        $images_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/bmp'],
        $resources = [],
        $search,
        $found_items;

    protected $listeners = [
        'refresh-resources' => 'refresh',
        'open-folder' => 'openFolder',
        'deleteFile',
        'deleteFolder',
    ];

    public function updatedSearch()
    {
        $this->found_items = Media::where('name', 'like', "%$this->search%")
            ->where('collection_name', '!=', 'default')
            ->get();
    }
    
    public function openRenameModal($resource)
    {
        $this->emitTo('media-library.rename', 'openModal', $resource);
    }

    public function refresh()
    {
        $this->resources = E3dMedia::where('path', $this->current_path)
            ->orWhere(function ($query) {
                $query->where('path', 'LIKE',  $this->current_path . '/%')
                    ->where('path', 'NOT LIKE',  $this->current_path . '/%\/%');
            })
            ->orderBy('path')
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
        $this->current_path .= '/' . $folder_name;
        $this->refresh();
    }

    public function deleteFile(array $data)
    {
        // [E3dMedia Object, media id]
        $e3d_media = E3dMedia::find($data[0]['id']);
        $e3d_media->deleteMedia($data[1]);

        $this->emitTo('media-library.index', 'refresh-resources');
        $this->emit('success', "Removido exitosamente");
    }

    public function deleteFolder($folder)
    {
        $path = $this->current_path . '/' . $folder;
        $resources_to_delete = E3dMedia::where('path', 'LIKE',  $path . '%')
            ->get();

        $resources_to_delete->each(function ($resource){
            $resource->getMedia($resource->path)
            ->each(fn ($media) => $this->deleteFile([$resource, $media->id]));

            $resource->delete();
        });
        

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

        if ($splitted_current_path->count() > 1) {
            $splitted_current_path->shift();
            return '/' . $splitted_current_path->implode('/');
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
