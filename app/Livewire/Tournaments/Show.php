<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Tournament $tournament;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function togglePublic()
    {
        if (Auth::id() === $this->tournament->user_id || Auth::user()->is_admin) {
            $this->tournament->is_public = !$this->tournament->is_public;
            $this->tournament->save();
        }
    }

    public function render()
    {
        return view('livewire.tournaments.show');
    }
}
