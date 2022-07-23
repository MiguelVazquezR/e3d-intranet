<?php

namespace App\Http\Livewire\User;

use App\Models\PayRoll;
use App\Models\User;
use App\Models\UserHasSellOrderedProduct;
use Livewire\Component;

class ActivitiesReport extends Component
{
    public
        $open = false,
        $filter = 1,
        $activities = [],
        $user;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->user = new User;
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'user',
            ]);
        }
    }

    public function updatedFilter()
    {
        $this->loadActivities();
    }

    public function openModal(User $user)
    {
        $this->open = true;
        $this->user = $user;
        $this->loadActivities();
    }
    
    public function loadActivities()
    {
        if ($this->filter == 1) {
            $from = PayRoll::currentPayRoll()->start_period;
            $to = PayRoll::currentPayRoll()->end_period;
            $this->activities = $this->user->activities()->whereBetween('created_at', [$from,$to])->get()->all();
        }elseif ($this->filter == 2) {
            $from = PayRoll::findOrFail( (PayRoll::currentPayRoll()->id - 1) )->start_period;
            $to = PayRoll::findOrFail( (PayRoll::currentPayRoll()->id - 1) )->end_period;
            $this->activities = $this->user->activities()->whereBetween('created_at', [$from,$to])->get()->all();
        }else {
            $this->activities = $this->user->activities->all();
        }
    }

    public function render()
    {
        return view('livewire.user.activities-report');
    }
}
