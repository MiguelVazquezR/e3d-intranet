<?php

namespace App\Http\Livewire\Meeting;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\MovementHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateMeeting extends Component
{
    public $open = false,
        $title,
        $location,
        $url,
        $description,
        $start,
        $end,
        $user_id,
        $remote_meeting = 1,
        $user_list = [];

    protected $rules = [
        'title' => 'required|max:191',
        'description' => 'required',
        'start' => 'required',
        'end' => 'required',
        'user_list' => 'required',
    ];

    protected $listeners = [
        'render',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function updatedUserId($user_id)
    {
        if (!in_array($user_id, $this->user_list))
            $this->user_list[] = $user_id;
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function removeParticipant($index)
    {
        unset($this->user_list[$index]);
    }

    public function store()
    {
        if($this->remote_meeting) {
            $this->rules["url"] = 'required';
        } else {
            $this->rules["location"] = 'required';
        }
        
        $validated_data = $this->validate(null, [
            'user_list.required' => 'Debe de haber mínimo un participante para la reunión'
        ]);
        
        $validated_data["start"] = Carbon::parse($validated_data["start"])->toDateTimeString();
        $validated_data["end"] = Carbon::parse($validated_data["end"])->toDateTimeString();
        
        $meeting = Meeting::create($validated_data + [
            'user_id' => Auth::user()->id
        ]);
        
        // create meeting participants
        foreach ($this->user_list as $user_id) {
            MeetingParticipant::create([
                'user_id' => $user_id,
                'meeting_id' => $meeting->id,
            ]);
        }
        
        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se creó nueva reunión '{$meeting->title}'"
        ]);
        
        $this->reset();

        $this->emitTo('meeting.meetings', 'render');
        $this->emit('success', 'Nueva reunión creada');
    }

    public function render()
    {
        return view('livewire.meeting.create-meeting', [
            'users' => User::where('active', 1)->where('id', '!=', Auth::user()->id)->get(),
        ]);
    }
}
