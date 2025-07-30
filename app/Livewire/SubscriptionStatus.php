<?php

namespace App\Livewire;

use App\Services\PackageLimitService;
use Livewire\Component;

class SubscriptionStatus extends Component
{
    public function render()
    {
        $user = auth()->user();
        $packageService = new PackageLimitService();
        
        $subscription = $user->activeSubscription();
        $limits = $packageService->getUserLimits($user);
        
        return view('livewire.subscription-status', [
            'subscription' => $subscription,
            'limits' => $limits,
        ]);
    }
} 