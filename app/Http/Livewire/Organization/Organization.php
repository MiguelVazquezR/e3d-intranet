<?php

namespace App\Http\Livewire\Organization;

use App\Models\MovementHistory;
use App\Models\Organization as ModelsOrganization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class Organization extends Component
{
    use WithFileUploads;

    public
        $organization,
        $logo,
        $shield,
        $logo_image_id,
        $shield_image_id;

    public $image_extensions = [
        'png', 'jpg', 'jpeg', 'bmp'
    ];

    protected $rules = [
        'organization.name' => 'required',
        'organization.bussiness_name' => 'required',
        'organization.rfc' => 'required',
        'organization.address' => 'required',
        'organization.post_code' => 'required',
        'organization.phone1' => 'required',
        'organization.phone2' => 'max:191',
        'organization.web_site' => 'required',
    ];

    protected $listeners = [
        'render',
    ];

    public function mount()
    {
        $this->logo_id = rand();
        $this->shield_id = rand();
        $this->organization = ModelsOrganization::find(1);
    }

    public function update()
    {
        if ($this->shield) {
            $this->rules['shield'] = 'image';
        }
        if ($this->logo) {
            $this->rules['logo'] = 'image';
        }

        $this->validate();

        if ($this->shield) {
            Storage::delete([$this->organization->shield]);
            //storage  optimized image
            $image_name = time() . Str::random(10) . '.' . $this->shield->extension();
            $image_path = storage_path() . "\app\public\organization\\$image_name";
            Image::make($this->shield)->save($image_path);
            $this->organization->shield = "public/organization/$image_name";
        }

        if ($this->logo) {
            Storage::delete([$this->organization->logo]);
            //storage  optimized image
            $image_name = time() . Str::random(10) . '.' . $this->logo->extension();
            $image_path = storage_path() . "\app\public\organization\\$image_name";
            Image::make($this->logo)->save($image_path);
            $this->organization->logo = "public/organization/$image_name";
        }

        $this->organization->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se cambió información acerca de la organización"
        ]);

        $this->emit('success', 'Organización actualizada');
    }


    public function render()
    {
        return view('livewire.organization.organization');
    }
}
