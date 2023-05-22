<?php

namespace App\Http\Livewire\Machines\Maintenance;

use App\Models\Maintenance;
use App\Models\MovementHistory;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditMaintenance extends Component
{
    use WithFileUploads;

    public $open = false,
        $aquisition_date,
        $files = [],
        $maintenance;

    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'maintenance.problems' => 'required',
        'maintenance.actions' => 'required',
        'maintenance.cost' => 'required|numeric',
        'maintenance.maintenance_type' => 'required',
        'maintenance.responsible' => 'required',
        'files.*' => 'mimes:jpg,png,jpeg,gif,svg,pdf,docx,doc,txt,xlsx,xlsm,xlsb,xls'
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'maintenance',
            ]);
        }
    }

    public function mount()
    {
        $this->maintenance = new Maintenance();
    }

    public function openModal(Maintenance $maintenance)
    {
        $this->open = true;
        $this->maintenance = $maintenance;
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

        $this->maintenance->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => auth()->id(),
            'description' => "Se editó mantenimiento con ID: {$this->maintenance->id}"
        ]);

        $this->resetExcept(['maintenance']);

        $this->emitTo('machines.maintenance.index-maintenance', 'updateModel');
        $this->emit('success', 'mantenimiento actualizado');
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
        return view('livewire.machines.maintenance.edit-maintenance');
    }
}
