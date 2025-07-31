<?php

namespace App\Livewire\Tabspaces;

use Livewire\Component;
use App\Models\Tabspace;

class Show extends Component
{
    public Tabspace $tabspace;

    public function mount(Tabspace $tabspace)
    {
        $this->tabspace = $tabspace;
    }

    public function render()
    {
        return view('livewire.tabspaces.show'); 
    }
}
