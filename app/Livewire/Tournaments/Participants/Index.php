<?php

namespace App\Livewire\Tournaments\Participants;

use Flux\Flux;
use Livewire\Component;
use App\Models\Tournament;
use App\Models\Institution;
use App\Models\TournamentJudge;
use App\Models\TournamentPatron;
use App\Models\TournamentDebater;
use App\Models\ParticipantCategory;
use App\Models\TournamentTabMaster;
use App\Models\TournamentParticipant;

class Index extends Component
{
    public Tournament $tournament;
    public $institutions;
    public $participantCategories;
    
    // Form inputs
    public $name;
    public $email;
    public $phone;
    public $gender;
    public $role;
    public $institution;
    public $nickname;  // for role-specific table
    public $participantCategory; // optional for debaters

    // public $debaters =[],$judges =[],$patrons =[],$tabMasters=[];
    public $search = '';


    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->institutions = Institution::all();
        $this->participantCategories = ParticipantCategory::all();

        // defaults
        $this->role = "Debater";
        $this->institution = $this->institutions->first()?->id;
        $this->participantCategory = $this->participantCategories->first()?->id;
        $this->gender = "female";

    }



    public function autoGenerateEmail()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        // Clean the name (remove spaces, lowercase)
        $cleanName = strtolower(preg_replace('/\s+/', '.', $this->name));

        // Use your app domain
        $domain = config('app.domain', strtolower(config('app.name') . '.net')); // you can set this in config/app.php

        // Generate a unique-ish email
        $uniqueId = rand(100, 999); // optional to reduce collisions
        $this->email = $cleanName . $uniqueId . '@' . $domain;
    }

  public function storeParticipant()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|required_without:phone',
            'phone' => 'nullable|string|max:50|required_without:email',
            'gender' => 'required|in:male,female',
            'role' => 'required|in:Debater,Judge,Patron,Tab Master',
            'institution' => 'required|exists:institutions,id',
            'nickname' => 'nullable|string|max:255',
            'participantCategory' => 'nullable|exists:participant_categories,id',
        ], [
            'email.required_without' => 'You must provide either an email or a phone number.',
            'phone.required_without' => 'You must provide either a phone number or an email.',
        ]);

        // Check if participant already exists globally (by email or phone)
        $participant = TournamentParticipant::firstOrCreate(
            [
                'email' => $this->email ?? null,
                'phone' => $this->phone ?? null,
            ],
            [
                'tournament_id' => $this->tournament->id, // first tournament they joined
                'institution_id' => $this->institution,
                'name' => $this->name,
                'gender' => $this->gender,
            ]
        );

        // Create or update role-specific record for this tournament
        switch ($this->role) {
            case 'Debater':
                TournamentDebater::updateOrCreate(
                    [
                        'tournament_participant_id' => $participant->id,
                        'tournament_id' => $this->tournament->id,
                    ],
                    [
                        'institution_id' => $this->institution,
                        'nickname' => $this->nickname,
                        'participant_category_id' => $this->participantCategory ?? null,
                    ]
                );
                break;

            case 'Judge':
                TournamentJudge::updateOrCreate(
                    [
                        'tournament_participant_id' => $participant->id,
                        'tournament_id' => $this->tournament->id,
                    ],
                    [
                        'institution_id' => $this->institution,
                        'nickname' => $this->nickname,
                    ]
                );
                break;

            case 'Patron':
                TournamentPatron::updateOrCreate(
                    [
                        'tournament_participant_id' => $participant->id,
                        'tournament_id' => $this->tournament->id,
                    ],
                    [
                        'institution_id' => $this->institution,
                        'nickname' => $this->nickname,
                    ]
                );
                break;

            case 'Tab Master':
                TournamentTabMaster::updateOrCreate(
                    [
                        'tournament_participant_id' => $participant->id,
                        'tournament_id' => $this->tournament->id,
                    ],
                    [
                        'institution_id' => $this->institution,
                        'nickname' => $this->nickname,
                    ]
                );
                break;
        }

        // Reset form fields
        $this->reset(['name','email','phone','nickname']);

        // Trigger frontend events
        $this->dispatch('participantAdded');
        Flux::modals()->close(); // close modal if using Flux
        flash()->addSuccess('Participant added successfully!');
    }

    public function render()
    {
        $search = $this->search;

        return view('livewire.tournaments.participants.index',[
            'debaters' => TournamentDebater::with(['participant', 'participantCategory', 'institution'])
                ->where('tournament_id', $this->tournament->id)
                ->whereHas('participant', function ($q) use ($search) {
                    if ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    }
                })
                ->get()
                ->groupBy(fn($d) => $d->participantCategory?->name ?? 'Uncategorized'),


            'judges' => TournamentJudge::with(['participant', 'institution'])
                            ->where('tournament_id', $this->tournament->id)
                            ->whereHas('participant', function ($q) use ($search) {
                                if ($search) {
                                    $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                                }
                            })
                            ->get(),

            'patrons' => TournamentPatron::with(['participant', 'institution'])
                            ->where('tournament_id', $this->tournament->id)
                            ->whereHas('participant', function ($q) use ($search) {
                                if ($search) {
                                    $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                                }
                            })
                            ->get(),

            'tabMasters' => TournamentTabMaster::with(['participant', 'institution'])
                            ->where('tournament_id', $this->tournament->id)
                            ->whereHas('participant', function ($q) use ($search) {
                                if ($search) {
                                    $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                                }
                            })
                            ->get(),

        ]);
    }
}
