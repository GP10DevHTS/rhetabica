<?php

namespace App\Livewire\Tabspaces;

use App\Models\Tabspace;
use Livewire\Component;

class Edit extends Component
{
    public Tabspace $tabspace;
    public $name;
    public $context;
    public $is_public;

    protected $rules = [
        'name' => 'required|min:3',
        'context' => 'required',
        'is_public' => 'boolean'
    ];

    public function mount(Tabspace $tabspace)
    {
        $this->tabspace = $tabspace;
        $this->name = $tabspace->name;
        $this->context = $tabspace->context;
        $this->is_public = $tabspace->is_public;
    }

    public function save()
    {
        $this->validate();

        $this->tabspace->name = $this->name;
        $this->tabspace->context = $this->context;
        $this->tabspace->is_public = $this->is_public;
        $this->tabspace->save();

        return redirect()->route('tabspaces.show', $this->tabspace);
    }

    public function render()
    {
        return view('livewire.tabspaces.edit');
    }
}
