<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;

class Show extends Component
{
    public Tournament $tournament;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function render()
    {
        return view('livewire.tournaments.show');
    }
}
