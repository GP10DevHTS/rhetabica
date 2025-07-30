<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $subscriptions = Subscription::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('livewire.subscriptions.index', compact('subscriptions'))
            ->layout('components.layouts.app', ['title' => 'Subscription Management']);
    }

    public function deleteSubscription($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $subscription->delete();
        
        session()->flash('success', 'Subscription deleted successfully.');
    }
} 