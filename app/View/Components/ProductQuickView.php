<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductQuickView extends Component
{
    public $image,
        $name,
        $name_bolded,
        $new_item;

    public function __construct($image = null, $name = '', $newItem = false, $nameBolded = true)
    {
        $this->image = $image;
        $this->name = $name;
        $this->name_bolded = $nameBolded;
        $this->new_item = $newItem;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product-quick-view');
    }
}
