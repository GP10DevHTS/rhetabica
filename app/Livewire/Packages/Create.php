<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $price = 0;
    public $max_tab_spaces = 1;
    public $max_tournaments_per_tab = 5;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:packages',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'max_tab_spaces' => 'required|integer|min:1',
        'max_tournaments_per_tab' => 'required|integer|min:1',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        return view('livewire.packages.create')
            ->layout('components.layouts.app', ['title' => 'Create Package']);
    }

    public function save()
    {
        $this->validate();

        Package::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'max_tab_spaces' => $this->max_tab_spaces,
            'max_tournaments_per_tab' => $this->max_tournaments_per_tab,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Package created successfully.');
        return $this->redirect(route('packages.index'));
    }
} 