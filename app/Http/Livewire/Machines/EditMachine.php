<?php

namespace App\Http\Livewire\Machines;

use App\Models\Machine;
use App\Models\MovementHistory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EditMachine extends Component
{
    use WithFileUploads;

    public $open = false,
        $aquisition_date,
        $files = [],
        $machine;
       
    protected $listeners = [
        'render',
        'openModal',
    ];

    protected $rules = [
        'machine.name' => 'required',
        'machine.serial_number' => 'nullable',
        'machine.weight' => 'nullable',
        'machine.width' => 'nullable',
        'machine.large' => 'nullable',
        'machine.height' => 'nullable',
        'machine.cost' => 'nullable',
        'machine.days_next_maintenance' => 'required|numeric|min:15',
        'aquisition_date' => 'required',
        'files.*' => 'mimes:jpg,png,jpeg,gif,svg,pdf,docx,doc,txt,xlsx,xlsm,xlsb,xls'
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'machine',
            ]);
        }
    }

    public function mount()
    {
        $this->machine = new Machine();
    }

    public function openModal(Machine $machine)
    {
        $this->open = true;
        $this->machine = $machine;
        $this->aquisition_date = $machine->aquisition_date 
        ? $machine->aquisition_date->toDateString()
        : '';
    }

    public function update()
    {
        $this->validate();

         // add files attached to machine (manuals, images, features, etc)
         foreach ($this->files as $file) {
            $this->machine->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('files');
        }

        $this->machine->aquisition_date = $this->aquisition_date;
        $this->machine->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => auth()->id(),
            'description' => "Se editó maquina con ID: {$this->machine->id}"
        ]);

        $this->resetExcept(['machine']);

        $this->emitTo('machines.machine-index', 'render');
        $this->emit('success', 'máquina actualizada');
    }

    public function deleteFile($media_uuid)
    {
        $media = Media::findByUuid($media_uuid);
        $media->delete();

        $this->machine = Machine::find($this->machine->id);
        $this->emit('success', 'Archivo eliminado');
    }

    public function render()
    {
        return view('livewire.machines.edit-machine');
    }
}
