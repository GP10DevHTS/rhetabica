<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;

class Create extends Component
{
    public $tabspaceId;
    public $name = '';
    public $description = '';
    public $is_public = false;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
        'is_public' => 'boolean'
    ];

    public function mount($tabspaceId)
    {
        $this->tabspaceId = $tabspaceId;
    }

    public function save()
    {
        $this->validate();

        $tournament = new Tournament();
        $tournament->name = $this->name;
        $tournament->description = $this->description;
        $tournament->is_public = $this->is_public;
        $tournament->tabspace_id = $this->tabspaceId;
        $tournament->save();

        return redirect()->route('tournaments.show', $tournament);
    }

    public function render()
    {
        return view('livewire.tournaments.create');
    }
}
