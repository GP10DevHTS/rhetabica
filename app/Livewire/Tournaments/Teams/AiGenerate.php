<?php

namespace App\Livewire\Tournaments\Teams;

use Livewire\Component;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentDebater;
use App\Services\AI\GeminiTeamNameGenerator;

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

        $this->institutions = $tournament->participantInstitutions()->pluck('name', 'id')->toArray();
        $this->categories   = $tournament->participantCategories()->pluck('name', 'id')->toArray();

        $this->excludedNames = implode(', ', $this->loadExistingTeamNames());
    }

    public function loadExistingTeamNames(): array
    {
        return TournamentTeam::where('tournament_id', $this->tournament->id)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Generate one team per institution
     */
    public function generate1TeamPerInstitution()
    {
        foreach ($this->institutions as $institutionId => $institutionName) {
            $this->generatedTeams = array_merge(
                $this->generatedTeams,
                $this->callAiGenerator("{$institutionName} Team", 1)
            );
        }
    }

    /**
     * Generate one team per category
     */
    public function generate1TeamPerCategory()
    {
        foreach ($this->categories as $categoryId => $categoryName) {
            $this->generatedTeams = array_merge(
                $this->generatedTeams,
                $this->callAiGenerator("{$categoryName} Team", 1)
            );
        }
    }

    /**
     * Generate one team per institution per category
     */
    public function generate1TeamPerInstitutionPerCategory()
    {
        foreach ($this->institutions as $institutionName) {
            foreach ($this->categories as $categoryName) {
                $this->generatedTeams = array_merge(
                    $this->generatedTeams,
                    $this->callAiGenerator("{$institutionName} - {$categoryName}", 1)
                );
            }
        }
    }

    /**
     * General AI generation (based on modal inputs)
     */
    public function generateTeams()
    {
        $this->generatedTeams = $this->callAiGenerator($this->tournament->name, $this->quantity);
    }

    /**
     * Wrapper for AI call
     */
    protected function callAiGenerator(string $context, int $count): array
    {
        $generator = app(GeminiTeamNameGenerator::class);

        $existingNames = $this->loadExistingTeamNames();
        $excludedArray = array_filter(array_map('trim', explode(',', $this->excludedNames)));

        $takenNames = array_merge($existingNames, $excludedArray);

        return $generator->generate(
            $context,
            $count,
            $this->style ?: null,
            $takenNames,
        );
    }

    public function closeModal()
    {
        $this->reset([
            'selectedInstitution',
            'selectedCategory',
            'excludedNames',
            'quantity',
            'style',
            'generatedTeams'
        ]);
        $this->dispatchBrowserEvent('closeModal', ['modal' => 'generate-teams-modal']);
    }

    public function render()
    {
        return view('livewire.tournaments.teams.ai-generate');
    }
}
