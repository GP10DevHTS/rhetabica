<?php

namespace App\Livewire\Tournaments\Teams;

use Flux\Flux;
use Livewire\Component;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\ParticipantCategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Index extends Component
{
    public Tournament $tournament;

    public $participantCategories = [];
    public $tournamentInstitution;
    public $teamName;
    public $participantCategory;
    public $editingTeamId;
    public $deleteTeamId;
    public $deleteTeamName;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->participantCategories = ParticipantCategory::pluck('name', 'id');
    }

    public function createTeam()
    {
        $this->validateTeam();

        TournamentTeam::create([
            'tournament_id' => $this->tournament->id,
            'name' => $this->teamName,
            'participant_category_id' => $this->participantCategory,
            'tournament_institution_id' => $this->tournamentInstitution,
        ]);

        flash()->addSuccess('Team created successfully.');
        $this->closeModal();
    }

    public function editTeam($id)
    {
        $team = TournamentTeam::findOrFail($id);
        $this->editingTeamId = $team->id;
        $this->teamName = $team->name;
        $this->participantCategory = $team->participant_category_id;
        $this->tournamentInstitution = $team->tournament_institution_id;

        Flux::modal('edit-team-modal')->show();
    }

    public function updateTeam()
    {
        $this->validateTeam($this->editingTeamId);

        $team = TournamentTeam::findOrFail($this->editingTeamId);
        $team->update([
            'name' => $this->teamName,
            'participant_category_id' => $this->participantCategory,
            'tournament_institution_id' => $this->tournamentInstitution,
        ]);

        flash()->addSuccess('Team updated successfully.');
        $this->closeModal();
    }

    public function confirmDeleteTeam($id)
    {
        $team = TournamentTeam::findOrFail($id);
        $this->deleteTeamId = $team->id;
        $this->deleteTeamName = $team->name;

        Flux::modal('delete-team-modal')->show();
    }

    public function deleteTeam()
    {
        TournamentTeam::findOrFail($this->deleteTeamId)->delete();
        flash()->addSuccess('Team deleted successfully.');
        $this->closeModal();
    }

    protected function validateTeam($ignoreId = null)
    {
        $this->teamName = trim($this->teamName);

        $this->validate([
            'teamName' => [
                'required',
                function ($attribute, $value, $fail) use ($ignoreId) {
                    $slug = Str::slug($value);
                    $exists = TournamentTeam::where('slug', $slug)
                        ->where('tournament_id', $this->tournament->id)
                        ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                        ->exists();

                    if ($exists) {
                        $fail("The team name has already been taken for this tournament.");
                    }
                },
            ],
            'tournamentInstitution' => [
                'nullable',
                Rule::exists('tournament_institutions', 'id')
                    ->where('tournament_id', $this->tournament->id),
            ],
            'participantCategory' => 'nullable|exists:participant_categories,id',
        ]);
    }

    public function closeModal()
    {
        Flux::modals()->close();
        $this->reset([
            'tournamentInstitution',
            'participantCategory',
            'teamName',
            'editingTeamId',
            'deleteTeamId',
            'deleteTeamName',
        ]);
    }

    public function render()
    {
        return view('livewire.tournaments.teams.index');
    }
}
