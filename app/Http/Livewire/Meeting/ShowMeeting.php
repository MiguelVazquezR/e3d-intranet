<?php

namespace App\Http\Livewire\Meeting;

use App\Models\Meeting;
use Livewire\Component;

class ShowMeeting extends Component
{
    public $open = false,
        $meeting;

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'meeting',
            ]);
        }
    }

    public function openModal(Meeting $meeting)
    {
        $this->open = true;
        $this->meeting = $meeting;
    }

    public function render()
    {
        return view('livewire.meeting.show-meeting');
    }
}
