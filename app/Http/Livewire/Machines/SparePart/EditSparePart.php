<?php

namespace App\Http\Livewire\Machines\SparePart;

use App\Models\MovementHistory;
use App\Models\SparePart;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSparePart extends Component
{
    use WithFileUploads;

    public $open = false,
        // $aquisition_date,
        // $files = [],
        $spare_part;

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'spare_part.name' => 'required',
        'spare_part.quantity' => 'required|numeric|min:1',
        'spare_part.supplier' => 'required',
        'spare_part.cost' => 'required|numeric',
        'spare_part.location' => 'required',
        'spare_part.description' => 'required',
    ];

    public function mount()
    {
        $this->spare_part = new SparePart();
    }

    public function openModal(SparePart $spare_part)
    {
        $this->open = true;
        $this->spare_part = $spare_part;
    }

    public function update()
    {
        $this->validate();

        // // add files attached to machine (manuals, images, features, etc)
        // foreach ($this->files as $file) {
        //     $this->machine->addMedia($file->getRealPath())
        //         ->usingName($file->getClientOriginalName())
        //         ->toMediaCollection('files');
        // }

        $this->spare_part->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => auth()->id(),
            'description' => "Se editó refacción de nombre: {$this->spare_part->name}"
        ]);

        $this->resetExcept(['spare_part']);

        $this->emitTo('machines.spare-part.index-spare-part', 'updateModel');
        $this->emit('success', 'Refacción actualizadas');
    }

    // public function deleteFile($media_uuid)
    // {
    //     $media = Media::findByUuid($media_uuid);
    //     $media->delete();

    //     $this->machine = Machine::find($this->machine->id);
    //     $this->emit('success', 'Archivo eliminado');
    // }

    public function render()
    {
        return view('livewire.machines.spare-part.edit-spare-part');
    }
}
