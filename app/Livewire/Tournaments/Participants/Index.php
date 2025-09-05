<?php

namespace App\Livewire\Tournaments\Participants;

use Livewire\Component;
use App\Models\Tournament;

class Index extends Component
{
    public Tournament $tournament;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function render()
    {
        return view('livewire.tournaments.participants.index');
    }
}
