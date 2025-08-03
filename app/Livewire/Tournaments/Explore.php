<?php

namespace App\Livewire\Tournaments;

use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Explore extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $query = Tournament::query();

        if (!Auth::check() || !Auth::user()->is_admin) {
            $query->where('is_public', true);
        }

        $tournaments = $query->when($this->search, function($query) {
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
