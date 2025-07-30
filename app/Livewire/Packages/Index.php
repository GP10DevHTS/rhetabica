<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $packages = Package::orderBy('created_at', 'desc')->paginate(10);
        
        return view('livewire.packages.index', compact('packages'))
            ->layout('components.layouts.app', ['title' => 'Package Management']);
    }

    public function deletePackage($packageId)
    {
        $package = Package::findOrFail($packageId);
        
        // Check if package has active subscriptions
        if ($package->subscriptions()->where('status', 'active')->exists()) {
            session()->flash('error', 'Cannot delete package with active subscriptions.');
            return;
        }

        $package->delete();
        session()->flash('success', 'Package deleted successfully.');
    }
} 