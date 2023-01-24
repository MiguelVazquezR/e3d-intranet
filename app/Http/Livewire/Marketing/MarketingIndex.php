<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;
use Livewire\WithPagination;

class MarketingIndex extends Component
{
    use WithPagination;

    public $projects_tab = false;

    public function toggleTrue()
    {
        $this->projects_tab = true;
    }
    
    public function toggleFalse()
    {
        $this->projects_tab = false;
    }

    public function render()
    {
        return view('livewire.marketing.marketing-index');
    }
}
