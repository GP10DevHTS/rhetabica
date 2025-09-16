<?php

namespace App\Livewire\Tournaments;

use Livewire\Component;
use App\Models\Tournament;

class Overview extends Component
{
    public Tournament $tournament;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function render()
    {
        return view('livewire.tournaments.overview');
    }
}
