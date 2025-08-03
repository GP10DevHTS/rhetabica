<?php

namespace App\Livewire\Tabspaces;

use App\Models\Tabspace;
use App\Models\Tournament;
use App\Services\PackageLimitService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Rule;

class TournamentList extends Component
{
    public Tabspace $tabspace;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|string')]
    public $description = '';

    public function mount(Tabspace $tabspace)
    {
        $this->tabspace = $tabspace;
    }

    public function save()
    {
        if (!$this->canCreateTournament()) {
            session()->flash('tournament-limit-reached', 'You have reached the maximum number of tournaments allowed for this tabspace.');
            return;
        }

        $this->validate();

        $this->tabspace->tournaments()->create([
            'name' => $this->name,
            'description' => $this->description,
            'user_id' => Auth::id(),
        ]);

        $this->reset(['name', 'description']);
        session()->flash('status', 'Tournament created successfully.');
    }

    public function canCreateTournament()
    {
        return app(PackageLimitService::class)->canCreateTournamentInTabSpace(Auth::user(), $this->tabspace->id);
    }

    public function render()
    {
        $tournaments = $this->tabspace->tournaments()->latest()->get();
        return view('livewire.tabspaces.tournament-list', [
            'tournaments' => $tournaments,
        ]);
    }
}
