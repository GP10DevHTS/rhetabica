<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;

class Show extends Component
{
    public Package $package;

    public function mount(Package $package)
    {
        $this->package = $package;
    }

    public function render()
    {
        return view('livewire.packages.show')
            ->layout('components.layouts.app', ['title' => $this->package->name]);
    }

    public function deletePackage()
    {
        // Check if package has active subscriptions
        if ($this->package->subscriptions()->where('status', 'active')->exists()) {
            session()->flash('error', 'Cannot delete package with active subscriptions.');
            return;
        }

        $this->package->delete();
        session()->flash('success', 'Package deleted successfully.');
        return $this->redirect(route('packages.index'));
    }
} 