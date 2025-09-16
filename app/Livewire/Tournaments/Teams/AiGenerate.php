<?php

namespace App\Livewire\Tournaments\Teams;

use Flux\Flux;
use Livewire\Component;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Services\AI\GeminiTeamNameGenerator;

class AiGenerate extends Component
{
    public Tournament $tournament;

    public $selectedInstitution = null;
    public $selectedCategory = null;
    public $excludedNames = '';
    public $quantity = 5;
    public $teamsPerInstitution = 1;
    public $style = '';

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

    // ---------------- AI GENERATION ----------------

    public function generate1TeamPerInstitution()
    {
        foreach ($this->institutions as $institutionId => $institutionName) {
            $names = $this->callAiGenerator("{$institutionName} Team", 1);
            $this->generatedTeams = array_merge($this->generatedTeams, $names);
            $this->createTeams($names, $institutionId);
        }
    }

    public function generate1TeamPerCategory()
    {
        foreach ($this->categories as $categoryId => $categoryName) {
            $names = $this->callAiGenerator("{$categoryName} Team", 1);
            $this->generatedTeams = array_merge($this->generatedTeams, $names);
            $this->createTeams($names, null, $categoryId);
        }
    }

    public function generate1TeamPerInstitutionPerCategory()
    {
        foreach ($this->institutions as $institutionId => $institutionName) {
            foreach ($this->categories as $categoryId => $categoryName) {
                $names = $this->callAiGenerator("{$institutionName} - {$categoryName}", 1);
                $this->generatedTeams = array_merge($this->generatedTeams, $names);
                $this->createTeams($names, $institutionId, $categoryId);
            }
        }
    }

    public function generateTeams()
    {
        $names = $this->callAiGenerator($this->tournament->name, $this->quantity);
        $this->generatedTeams = $names;
        $this->createTeams($names);
    }

    protected function callAiGenerator(string $context, int $count): array
    {
        $generator = app(GeminiTeamNameGenerator::class);

        $existingNames = $this->loadExistingTeamNames();
        $excludedArray = array_filter(array_map('trim', explode(',', $this->excludedNames)));
        $takenNames = array_merge($existingNames, $excludedArray);

        return $generator->generate(
            $context,
            $count,
            $this->style ?: null, // style
            $takenNames           // excluded names
        );
    }

    // ---------------- TEAM CREATION ----------------

    /**
     * Create teams in the database
     */
    protected function createTeams(array $names, ?int $institutionId = null, ?int $categoryId = null)
    {
        foreach ($names as $name) {
            TournamentTeam::firstOrCreate(
                [
                    'tournament_id' => $this->tournament->id,
                    'name'          => $name,
                ],
                [
                    'tournament_institution_id' => $institutionId,
                    'participant_category_id'   => $categoryId,
                ]
            );
        }
    }

    // ---------------- MODAL & RENDER ----------------

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

        Flux::modal('generate-teams-modal')->close();
    }

    public function render()
    {
        return view('livewire.tournaments.teams.ai-generate');
    }
}
