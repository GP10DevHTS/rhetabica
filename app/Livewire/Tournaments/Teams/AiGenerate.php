<?php

namespace App\Livewire\Tournaments\Teams;

use Livewire\Component;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentDebater;

class AiGenerate extends Component
{
    public Tournament $tournament;

    // Modal inputs
    public $selectedInstitution = null;
    public $selectedCategory = null;
    public $excludedNames = '';
    public $quantity = 5;
    public $teamsPerInstitution = 1; // default 1 team per institution
    public $style = '';

    // Options for selects
    public $institutions = [];
    public $categories = [];

    public $generatedTeams = [];

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;

        // Load options for selects
        $this->institutions = $tournament->participantInstitutions()->pluck('name', 'id')->toArray();
        $this->categories = $tournament->participantCategories()->pluck('name', 'id')->toArray();

        $this->excludedNames = TournamentTeam::where('tournament_id', $tournament->id)
            ->pluck('name')
            ->implode(', ');
        // dd($this->categories, $this->institutions);
    }

    public function generateTeams()
    {
        // Get already taken names to avoid duplicates
        $existingNames = TournamentTeam::where('tournament_id', $this->tournament->id)
            ->pluck('name')
            ->toArray();

        $exclude = array_merge($existingNames, array_map('trim', explode(',', $this->excludedNames)));

        // Prepare prompt/data for AI service
        $prompt = [
            'institution' => $this->selectedInstitution ? $this->institutions[$this->selectedInstitution] : 'Any',
            'category' => $this->selectedCategory ? $this->categories[$this->selectedCategory] : 'Any',
            'exclude' => $exclude,
            'quantity' => $this->quantity,
            'style' => $this->style ?: 'Neutral',
        ];

        // Simulate AI generation (replace with actual AI API call)
        $this->generatedTeams = $this->simulateAIGeneration($prompt);

        flash()->addSuccess('Teams generated successfully!');
    }

    protected function simulateAIGeneration($prompt)
    {
        // Dummy generation for testing
        $teams = [];
        $prefix = $prompt['institution'] !== 'Any' ? $prompt['institution'] . ' ' : '';
        $suffixStyle = $prompt['style'] ? ' (' . ucfirst($prompt['style']) . ')' : '';

        for ($i = 1; $i <= $prompt['quantity']; $i++) {
            $teams[] = $prefix . 'Team ' . $i . $suffixStyle;
        }

        // Remove duplicates if any
        $teams = array_diff($teams, $prompt['exclude']);

        return $teams;
    }

    public function closeModal()
    {
        $this->reset(['selectedInstitution', 'selectedCategory', 'excludedNames', 'quantity', 'style', 'generatedTeams']);
        $this->dispatchBrowserEvent('closeModal', ['modal' => 'generate-teams-modal']);
    }

    public function render()
    {
        return view('livewire.tournaments.teams.ai-generate');
    }
}
