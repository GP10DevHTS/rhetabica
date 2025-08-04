<?php

namespace App\Livewire\Tabspaces;

use Livewire\Component;
use App\Models\Tabspace;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public Tabspace $tabspace;

    public function mount(Tabspace $tabspace)
    {
        $this->tabspace = $tabspace;
    }

    public function togglePublic()
    {
        if (Auth::id() === $this->tabspace->user_id || Auth::user()->is_admin) {
            $this->tabspace->is_public = !$this->tabspace->is_public;
            $this->tabspace->save();
        }
    }

    public function render()
    {
        return view('livewire.tabspaces.show');
    }
}
