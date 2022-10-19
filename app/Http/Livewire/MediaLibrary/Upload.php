<?php

namespace App\Http\Livewire\MediaLibrary;

use App\Models\E3dMedia;
use Livewire\Component;
use Livewire\WithFileUploads;

class Upload extends Component
{
    use WithFileUploads;

    public $open = false,
        $files = [],
        $files_id,
        $sub_folder = null,
        $current_path = 'ML-index';

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'files' => 'required',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept(['open']);
            $this->files_id = rand();
        }
    }

    public function openModal($current_path)
    {
        $this->open = true;
        $this->current_path = $current_path;
    }

    public function store()
    {
        $this->validate();

        $path = $this->sub_folder
            ? $this->current_path . '/' . $this->sub_folder
            : $this->current_path;

        $media = E3dMedia::create([
            'user_id' => auth()->id(),
            'num_files' => count($this->files),
            'path' => $path,
        ]);

        foreach ($this->files as $file) {
            $media->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection($path);
        }

        $this->files_id = rand();
        $this->reset();

        $this->emitTo('media-library.index', 'refresh-resources');
        $this->emit('success', "Medios subidos correctamente");
    }

    public function render()
    {
        return view('livewire.media-library.upload');
    }
}
