<?php

namespace App\Livewire\Tournaments\Rooms;

use Flux\Flux;
use Livewire\Component;
use App\Models\Tournament;
use Illuminate\Support\Str;
use App\Models\TournamentRoom;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public Tournament $tournament;

    public $rooms = [];
    public $name;
    public $nickname;
    public $editingId = null;

    public $bulkRoomCount; // for AI generation
    public $deleteRoomName; // for delete modal

    public function mount(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->loadRooms();
    }

    public function loadRooms()
    {
        $this->rooms = $this->tournament->rooms()->latest()->get();
    }

    public function createRoom()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
        ]);

        TournamentRoom::create([
            'name' => $this->name,
            'nickname' => $this->nickname,
            'tournament_id' => $this->tournament->id,
            'user_id' => Auth::id(),
        ]);

        $this->closeModal();
        flash()->addSuccess('Room created successfully.');
        $this->loadRooms();
    }

    public function editRoom($id)
    {
        $room = TournamentRoom::findOrFail($id);
        $this->editingId = $room->id;
        $this->name = $room->name;
        $this->nickname = $room->nickname;

        Flux::modal('edit-room-modal')->show();
    }

    public function updateRoom()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
        ]);

        $room = TournamentRoom::findOrFail($this->editingId);
        $room->update([
            'name' => $this->name,
            'nickname' => $this->nickname,
        ]);

        $this->closeModal();
        flash()->addSuccess('Room updated successfully.');
        $this->loadRooms();
    }

    public function confirmDeleteRoom($id, $name)
    {
        $this->editingId = $id;
        $this->deleteRoomName = $name;
        Flux::modal('delete-room-modal')->show();
    }

    public function deleteRoom($id)
    {
        $room = TournamentRoom::findOrFail($id);
        $room->delete();

        $this->closeModal();
        flash()->addSuccess('Room deleted successfully.');
        $this->loadRooms();
    }

    /**
     * AI-assisted bulk room creation
     */
    public function generateRoomsWithAI()
    {
        $this->validate([
            'bulkRoomCount' => 'required|integer|min:1|max:50',
        ]);

        $nicknames = $this->generateAINicknames($this->bulkRoomCount);
        $x = $this->tournament->rooms()->count();

        foreach (range($x + 1, $x + $this->bulkRoomCount) as $i) {
            TournamentRoom::create([
                'name' => "Room $i",
                'nickname' => $nicknames[$i - 1] ?? null,
                'tournament_id' => $this->tournament->id,
                'user_id' => Auth::id(),
            ]);
        }

        $this->closeModal();
        flash()->addSuccess('Rooms generated successfully.');
        $this->loadRooms();
    }

    /**
     * Simulated AI nickname generator
     */
    protected function generateAINicknames($count)
    {
        $baseNames = [
            'The Grand Oratorium', 'The Persuasion Hall', 'The Logic Arena',
            'The Eloquence Chamber', 'Debate Den', 'Rhetoric Room', 'Reason Retreat'
        ];

        shuffle($baseNames);

        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = $baseNames[$i % count($baseNames)];
        }

        return $results;
    }

    public function closeModal()
    {
        Flux::modals()->close();
        $this->reset(['name', 'nickname', 'editingId', 'bulkRoomCount', 'deleteRoomName']);
    }

    public function render()
    {
        return view('livewire.tournaments.rooms.index', [
            'rooms' => $this->rooms
        ]);
    }
}
