<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageUploader extends Component
{
    public $image,
        $image_extensions,
        $registered_image,
        $image_id,
        $show_alerts,
        $model,
        $label;

    public function __construct(
        $image, 
        $imageExtensions, 
        $imageId, 
        $registeredImage = null,
        $showAlerts = true,
        $model = 'image',
        $label = 'Imagen'
        )
    {
        $this->image = $image;
        $this->image_extensions = $imageExtensions;
        $this->image_id = $imageId;
        $this->registered_image = $registeredImage;
        $this->show_alerts = $showAlerts;
        $this->model = $model;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.image-uploader');
    }
}
