<?php

namespace App\Services;

use App\Models\User;

class PackageLimitService
{
    /**
     * Check if user can create more tab spaces
     */
    public function canCreateTabSpace(User $user): bool
    {
        // Admins can always create tab spaces
        if ($user->is_admin) {
            return true;
        }
        
        $activeSubscription = $user->activeSubscription();
        
        if (!$activeSubscription) {
            return false;
        }
        
        $package = $activeSubscription->package;
        
        // Unlimited tab spaces
        if ($package->max_tab_spaces === -1) {
            return true;
        }
        
        // Count current tab spaces (you'll need to implement this when you create the TabSpace model)
        $currentTabSpacesCount = 0; // $user->tabSpaces()->count();
        
        return $currentTabSpacesCount < $package->max_tab_spaces;
    }
    
    /**
     * Check if user can create more tournaments in a specific tab space
     */
    public function canCreateTournamentInTabSpace(User $user, $tabSpaceId = null): bool
    {
        // Admins can always create tournaments
        if ($user->is_admin) {
            return true;
        }
        
        $activeSubscription = $user->activeSubscription();
        
        if (!$activeSubscription) {
            return false;
        }
        
        $package = $activeSubscription->package;
        
        // Unlimited tournaments per tab
        if ($package->max_tournaments_per_tab === -1) {
            return true;
        }
        
        // Count current tournaments in this tab space (you'll need to implement this when you create the Tournament model)
        $currentTournamentsCount = 0; // $user->tournaments()->where('tab_space_id', $tabSpaceId)->count();
        
        return $currentTournamentsCount < $package->max_tournaments_per_tab;
    }
    
    /**
     * Get remaining tab space slots for user
     */
    public function getRemainingTabSpaceSlots(User $user): int
    {
        if ($user->is_admin) {
            return -1; // Unlimited
        }
        
        $activeSubscription = $user->activeSubscription();
        
        if (!$activeSubscription) {
            return 0;
        }
        
        $package = $activeSubscription->package;
        
        if ($package->max_tab_spaces === -1) {
            return -1; // Unlimited
        }
        
        $currentTabSpacesCount = 0; // $user->tabSpaces()->count();
        
        return max(0, $package->max_tab_spaces - $currentTabSpacesCount);
    }
    
    /**
     * Get remaining tournament slots for user in a specific tab space
     */
    public function getRemainingTournamentSlotsInTabSpace(User $user, $tabSpaceId = null): int
    {
        if ($user->is_admin) {
            return -1; // Unlimited
        }
        
        $activeSubscription = $user->activeSubscription();
        
        if (!$activeSubscription) {
            return 0;
        }
        
        $package = $activeSubscription->package;
        
        if ($package->max_tournaments_per_tab === -1) {
            return -1; // Unlimited
        }
        
        $currentTournamentsCount = 0; // $user->tournaments()->where('tab_space_id', $tabSpaceId)->count();
        
        return max(0, $package->max_tournaments_per_tab - $currentTournamentsCount);
    }
    
    /**
     * Get user's current package limits
     */
    public function getUserLimits(User $user): array
    {
        if ($user->is_admin) {
            return [
                'tab_spaces' => -1,
                'tournaments_per_tab' => -1,
                'remaining_tab_spaces' => -1,
                'remaining_tournaments_per_tab' => -1,
            ];
        }
        
        $activeSubscription = $user->activeSubscription();
        
        if (!$activeSubscription) {
            return [
                'tab_spaces' => 0,
                'tournaments_per_tab' => 0,
                'remaining_tab_spaces' => 0,
                'remaining_tournaments_per_tab' => 0,
            ];
        }
        
        $package = $activeSubscription->package;
        
        return [
            'tab_spaces' => $package->max_tab_spaces,
            'tournaments_per_tab' => $package->max_tournaments_per_tab,
            'remaining_tab_spaces' => $this->getRemainingTabSpaceSlots($user),
            'remaining_tournaments_per_tab' => $this->getRemainingTournamentSlotsInTabSpace($user),
        ];
    }
} 