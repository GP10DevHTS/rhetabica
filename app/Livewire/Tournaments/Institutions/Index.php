<?php

namespace App\Livewire\Tournaments\Institutions;

use Flux\Flux;
use Livewire\Component;
use App\Models\Tournament;
use App\Models\Institution;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\TournamentInstitution;

class Index extends Component
{
    public Tournament $tournament;
    public $institutions;

    // form inputs
    public $newInstitution;     // ID from dropdown
    public $newInstitutionName; // for creating a new institution
    public $invitationNotes;

    public $search;

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->institutions = Institution::all();
    }

    /**
     * Invite a new institution to the tournament
     */
    public function inviteInstitution()
    {
        $this->validate([
            'invitationNotes' => 'nullable|string|max:1000',
        ]);

        // Step 1: Find or create institution by slug
        if ($this->newInstitution) {
            $institutionId = $this->newInstitution;
        } elseif ($this->newInstitutionName) {
            $slug = Str::slug($this->newInstitutionName);

            $institution = Institution::firstOrCreate(
                ['uuid' => $slug],
                ['name' => $this->newInstitutionName]
            );

            $institutionId = $institution->id;
        } else {
            $this->addError('newInstitution', 'Please select or enter a new institution.');
            return;
        }

        // Step 2: Attach to tournament
        TournamentInstitution::firstOrCreate(
            [
                'tournament_id'  => $this->tournament->id,
                'institution_id' => $institutionId,
            ],
            [
                'invitation_notes' => $this->invitationNotes,
                'invited_at'       => now(),
                'invited_by'       => Auth::id(),
            ]
        );

        // Step 3: Reset fields
        $this->reset(['newInstitution', 'newInstitutionName', 'invitationNotes']);

        // Step 4: Refresh tournament
        $this->tournament->refresh();

        Flux::modals()->close();
        flash()->addSuccess('Institution invited successfully.');
    }


    public function markInvited($id)
    {
        $invite = TournamentInstitution::findOrFail($id);
        $invite->update([
            'invited_at' => now(),
            'invited_by' => Auth::id(),
        ]);
        $this->tournament->refresh();
        flash()->addSuccess('Institution marked as invited.');
    }

    public function markConfirmed($id)
    {
        $invite = TournamentInstitution::findOrFail($id);
        $invite->update([
            'confirmed_at' => now(),
            'confirmed_by' => Auth::id(),
        ]);
        $this->tournament->refresh();
        flash()->addSuccess('Institution marked as confirmed.');
    }

    public function markArrived($id)
    {
        $invite = TournamentInstitution::findOrFail($id);
        $invite->update([
            'arrived_at' => now(),
            'arrived_recorded_by' => Auth::id(),
        ]);
        $this->tournament->refresh();
        flash()->addSuccess('Institution marked as arrived.');
    }

    public function render()
    {
        $query = TournamentInstitution::with('institution')
            ->where('tournament_id', $this->tournament->id);

        if ($this->search && strlen($this->search) >= 2) {
            $query->where(function ($q) {
                $q->whereHas('institution', function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('name_override', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.tournaments.institutions.index', [
            'invitedInstitutions' => $query->get(),
        ]);
    }
}
