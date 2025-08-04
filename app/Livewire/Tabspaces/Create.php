<?php

namespace App\Livewire\Tabspaces;

use App\Models\Tabspace;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $context = '';
    public $is_public = false;

    protected $rules = [
        'name' => 'required|min:3',
        'context' => 'required',
        'is_public' => 'boolean'
    ];

    public function save()
    {
        $this->validate();

        $tabspace = new Tabspace();
        $tabspace->name = $this->name;
        $tabspace->context = $this->context;
        $tabspace->is_public = $this->is_public;
        $tabspace->save();

        return redirect()->route('tabspaces.show', $tabspace);
    }

    public function render()
    {
        return view('livewire.tabspaces.create');
    }
}
