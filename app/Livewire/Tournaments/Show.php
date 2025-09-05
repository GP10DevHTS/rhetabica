<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Url;

class Show extends Component
{
    public Tournament $tournament;

    #[Url(keep: true, as: 'tab')]
    public string $tab = 'overview'; // default tab

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function togglePublic()
    {
        if (Auth::id() === $this->tournament->user_id || Auth::user()->is_admin || Auth::id() === $this->tournament->tabspace->user_id) {
            $this->tournament->is_public = !$this->tournament->is_public;
            $this->tournament->save();
        }
    }

    public function switchTab(string $tab)
    {
        $this->tab = $tab; // update tab when clicked
    }

    public function render()
    {
        return view('livewire.tournaments.show');
    }
}
