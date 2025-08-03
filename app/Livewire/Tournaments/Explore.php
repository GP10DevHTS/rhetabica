<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Livewire\Component;
use Livewire\WithPagination;

class Explore extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $tournaments = Tournament::where('is_public', true)
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->with(['user', 'tabspace'])
            ->latest()
            ->paginate(12);

        return view('livewire.tournaments.explore', [
            'tournaments' => $tournaments
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
