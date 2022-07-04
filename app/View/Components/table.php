<?php

namespace App\View\Components;

use Illuminate\View\Component;

class table extends Component
{
    
    public $models,
        $sort,
        $columns,
        $direction;

    public function __construct($models, $sort, $direction, $columns)
    {
        $this->models = $models;
        $this->sort = $sort;
        $this->direction = $direction;
        $this->columns = $columns;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table');
    }
}
