<?php

namespace App\Livewire\Tabspaces;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $tabspaces;

    public function mount()
    {
        $this->tabspaces = Auth::user()->tabspaces;
    }

    public function render()
    {
        return view('livewire.tabspaces.index')
            ->layout('components.layouts.app');
    }
}
