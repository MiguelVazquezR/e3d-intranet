<?php

namespace App\Http\Livewire\MediaLibrary;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'render',
        'delete',
        'show',
        'edit',
    ];

    public function render()
    {
        return view('livewire.media-library.index');
    }
}
