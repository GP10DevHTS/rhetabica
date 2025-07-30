<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;

class Edit extends Component
{
    public Package $package;
    public $name;
    public $description;
    public $price;
    public $max_tab_spaces;
    public $max_tournaments_per_tab;
    public $is_active;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:packages,name,' . $this->package->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_tab_spaces' => 'required|integer|min:1',
            'max_tournaments_per_tab' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ];
    }

    public function mount(Package $package)
    {
        $this->package = $package;
        $this->name = $package->name;
        $this->description = $package->description;
        $this->price = $package->price;
        $this->max_tab_spaces = $package->max_tab_spaces;
        $this->max_tournaments_per_tab = $package->max_tournaments_per_tab;
        $this->is_active = $package->is_active;
    }

    public function render()
    {
        return view('livewire.packages.edit')
            ->layout('components.layouts.app', ['title' => 'Edit Package']);
    }

    public function save()
    {
        $this->validate();

        $this->package->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'max_tab_spaces' => $this->max_tab_spaces,
            'max_tournaments_per_tab' => $this->max_tournaments_per_tab,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Package updated successfully.');
        return $this->redirect(route('packages.index'));
    }
} 