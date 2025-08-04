<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;

class Edit extends Component
{
    public Tournament $tournament;
    public $name;
    public $description;
    public $is_public;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
        'is_public' => 'boolean'
    ];

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->name = $tournament->name;
        $this->description = $tournament->description;
        $this->is_public = $tournament->is_public;
    }

    public function save()
    {
        $this->validate();

        $this->tournament->name = $this->name;
        $this->tournament->description = $this->description;
        $this->tournament->is_public = $this->is_public;
        $this->tournament->save();

        return redirect()->route('tournaments.show', $this->tournament);
    }

    public function render()
    {
        return view('livewire.tournaments.edit');
    }
}
