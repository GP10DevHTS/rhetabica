<?php

namespace App\Livewire\Tabspaces;

use App\Models\Tabspace;
use Livewire\Component;
use Livewire\WithPagination;

class Explore extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $tabspaces = Tabspace::where('is_public', true)
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('context', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->latest()
            ->paginate(12);

        return view('livewire.tabspaces.explore', [
            'tabspaces' => $tabspaces
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
