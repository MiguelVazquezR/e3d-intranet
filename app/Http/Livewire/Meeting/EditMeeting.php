<?php

namespace App\Http\Livewire\Meeting;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\MovementHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditMeeting extends Component
{
    public $open = false,
        $meeting,
        $start,
        $end,
        $user_id,
        $remote_meeting,
        $user_list = [];

    protected $rules = [
        'meeting.title' => 'required|max:191',
        'meeting.description' => 'required',
        'meeting.location' => 'required',
        'meeting.url' => 'required',
        'start' => 'required',
        'end' => 'required',
        'user_list' => 'required',
    ];

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

    public function updatedUserId($user_id)
    {
        if (!in_array($user_id, $this->user_list))
            $this->user_list[] = $user_id;
    }

    public function openModal(Meeting $meeting)
    {
        $this->open = true;
        $this->meeting = $meeting;
        $this->start = '2022-07-09 05:15:00';
        $this->start = $meeting->start->toDateTimeLocalString();
        $this->end = $meeting->end->toDateTimeLocalString();
        if ($meeting->url) {
            $this->remote_meeting = true;
        } else {
            $this->remote_meeting = false;
        }

        // load participants
        foreach ($meeting->participants as $participant)
            $this->user_list[] = $participant->user->id;
    }

    public function removeParticipant($index)
    {
        unset($this->user_list[$index]);
    }

    public function update()
    {
        if($this->remote_meeting) {
            $this->rules["meeting.url"] = 'required';
            $this->meeting->location = null;
            unset($this->rules["meeting.location"]);
        } else {
            $this->rules["meeting.location"] = 'required';
            $this->meeting->url = null;
            unset($this->rules["meeting.url"]);
        }

        $this->validate(null, [
            'user_list.required' => 'Debe de haber mínimo un participante para la reunión'
        ]);

        $this->meeting->start = Carbon::parse($this->start)->toDateTimeString();
        $this->meeting->end = Carbon::parse($this->end)->toDateTimeString();

        $this->meeting->save();

        // update participants
        MeetingParticipant::where('meeting_id', $this->meeting->id)->delete();
        foreach ($this->user_list as $user_id) {
            MeetingParticipant::create([
                'user_id' => $user_id,
                'meeting_id' => $this->meeting->id,
            ]);
        }

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó su reunión '{$this->meeting->title}'"
        ]);

        $this->reset();

        $this->emitTo('meeting.meetings', 'render');
        $this->emit('success', 'Reunión actualizada');
    }

    public function render()
    {
        return view('livewire.meeting.edit-meeting', [
            'users' => User::all(),
        ]);
    }
}
