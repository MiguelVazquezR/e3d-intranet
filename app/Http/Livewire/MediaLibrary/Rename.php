<?php

namespace App\Http\Livewire\MediaLibrary;

use App\Models\E3dMedia;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Rename extends Component
{
    public $open = false,
        $media,
        $name;

    protected $listeners = [
        'openModal',
    ];

    protected $rules = [
        'name' => 'required',
    ];

    // public function updatingOpen()
    // {
    //     if ($this->open == true) {
    //         $this->resetExcept(['open']);
    //     }
    // }

    public function openModal($resource)
    {
        if( is_numeric($resource) ) {
            $this->media = Media::find($resource);
            $this->name = $this->media->name;
        } else {
            $this->folder = $resource;
            $splitted_path = explode('/', $resource);
            $this->name = end($splitted_path);
        }
        $this->open = true;
    }

    public function rename()
    {
        $this->validate();

        if ($this->media) {
            $this->media->name = $this->name;
            $this->media->save();
        } else {
            $folders = E3dMedia::where('path', $this->folder)->get();
            $folders->each(function ($folder) {
                $splitted_path = explode('/', $folder->path);
                $last_element = count($splitted_path) - 1;
                $splitted_path[$last_element] = $this->name;
                $new_path = implode('/', $splitted_path);
                $folder->getMedia($folder->path)->each(function ($media) use ($new_path){
                    $media->collection_name = $new_path;
                    $media->save();
                }); 
                $folder->path = $new_path;
                $folder->save();
            });
        }

        $this->reset('open');

        $this->emitTo('media-library.index', 'refresh-resources');
        $this->emit('success', "Cambio exitoso");
    }

    public function render()
    {
        return view('livewire.media-library.rename');
    }
}
