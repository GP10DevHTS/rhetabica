<?php

namespace App\Livewire\Tournaments\Teams;

use Flux\Flux;
use Livewire\Component;
use App\Models\TeamMember;
use App\Models\Tournament;
use App\Events\TeamsUpdated;
use App\Models\TournamentTeam;
use App\Models\TournamentDebater;
use App\Models\TournamentInstitution;
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
            $names = $this->callAiGenerator("{$institutionName} Team participating in {$this->tournament->name}. Avoid revealing or relating to the team's or institution's real name", 1);
            $this->generatedTeams = array_merge($this->generatedTeams, $names);
            $tournamentInstitution = TournamentInstitution::where('tournament_id', $this->tournament->id)
                ->where('institution_id', $institutionId)
                ->first();
            if ($tournamentInstitution) {
                $institutionId = $tournamentInstitution->id;
            } else {
                $institutionId = null;
            }
            $this->createTeams($names, $institutionId);
        }
        event(new TeamsUpdated($this->tournament));
    }

    public function generate1TeamPerCategory()
    {
        foreach ($this->categories as $categoryId => $categoryName) {
            $names = $this->callAiGenerator("{$categoryName} Team participating in {$this->tournament->name}. Avoid revealing or relating to the team's or institution's real name", 1);
            $this->generatedTeams = array_merge($this->generatedTeams, $names);
            $this->createTeams($names, null, $categoryId);
        }
        event(new TeamsUpdated($this->tournament));
    }

    public function generate1TeamPerInstitutionPerCategory()
    {
        foreach ($this->institutions as $institutionId => $institutionName) {

            $tournamentInstitution = TournamentInstitution::where('tournament_id', $this->tournament->id)
                ->where('institution_id', $institutionId)
                ->first();
            if ($tournamentInstitution) {
                $institutionId = $tournamentInstitution->id;
            } else {
                $institutionId = null;
            }

            foreach ($this->categories as $categoryId => $categoryName) {
                $names = $this->callAiGenerator("{$institutionName} - {$categoryName} Team participating in {$this->tournament->name}. Avoid revealing or relating to the team's or institution's real name", 1);
                $this->generatedTeams = array_merge($this->generatedTeams, $names);
                $this->createTeams($names, $institutionId, $categoryId);
            }
        }
        event(new TeamsUpdated($this->tournament));
    }

    public function generateTeams()
    {
        $names = $this->callAiGenerator($this->tournament->name, $this->quantity);
        $this->generatedTeams = $names;
        $this->createTeams($names);
        event(new TeamsUpdated($this->tournament));
    }

    protected function callAiGenerator(string $context, int $count): array
    {
        $generator = app(GeminiTeamNameGenerator::class);

        $existingNames = $this->loadExistingTeamNames();
        $excludedArray = array_filter(array_map('trim', explode(',', $this->excludedNames)));
        $takenNames = array_merge($existingNames, $excludedArray);

        // Pick a random style if none is given
        $style = $this->style ?: ['creative', 'funny', 'aggressive', 'playful', 'sarcastic', 'bantering', 'epic', 'mysterious', 'punny'][array_rand(['creative', 'funny', 'aggressive', 'playful', 'sarcastic', 'bantering', 'epic', 'mysterious', 'punny'])];

        return $generator->generate(
            $context,
            $count,
            $style,
            $takenNames
        );
    }


    // ---------------- TEAM CREATION ----------------

    protected function createTeams(array $names, ?int $institutionId = null, ?int $categoryId = null)
    {
        foreach ($names as $name) {
            // Check if a team already exists for this tournament + institution + category
            $teamExistsQuery = TournamentTeam::where('tournament_id', $this->tournament->id);
                // ->where('name', $name);

            if ($institutionId !== null) {
                $teamExistsQuery->where('tournament_institution_id', $institutionId);
            }

            if ($categoryId !== null) {
                $teamExistsQuery->where('participant_category_id', $categoryId);
            }

            if ($teamExistsQuery->exists()) {
                continue; // Skip creating the team
            }

            // Create the team
            $team = TournamentTeam::create([
                'tournament_id' => $this->tournament->id,
                'name'          => $name,
                'tournament_institution_id' => $institutionId,
                'participant_category_id'   => $categoryId,
            ]);

            // Assign team members
            $this->assignTeamMembers($team, $institutionId, $categoryId);
        }
    }


    /**
     * Assign team members from debaters in the same institution/category
     */
    protected function assignTeamMembers(TournamentTeam $team, ?int $institutionId, ?int $categoryId)
    {
        $debaterQuery = TournamentDebater::query()
            ->where('tournament_id', $this->tournament->id);

        if ($institutionId) {
            $debaterQuery->where('tournament_institution_id', $institutionId);
        }

        if ($categoryId) {
            $debaterQuery->where('participant_category_id', $categoryId);
        }

        // Limit to 3 members per team by default
        $debaterIds = $debaterQuery->inRandomOrder()->limit(3)->pluck('id');

        foreach ($debaterIds as $debaterId) {
            TeamMember::firstOrCreate([
                'tournament_team_id'   => $team->id,
                'tournament_debater_id'=> $debaterId,
            ]);
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

        Flux::modals()->close();

    }

    public function render()
    {
        return view('livewire.tournaments.teams.ai-generate');
    }
}
