<div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Subscription Status</h3>
    
    @if($subscription)
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Package:</span>
                <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $subscription->package->name }}</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</span>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $subscription->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $subscription->isActive() ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Expires:</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $subscription->end_date->format('M d, Y') }}</span>
            </div>
            
            @if($limits['tab_spaces'] !== -1)
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tab Spaces:</span>
                    <span class="text-sm text-gray-900 dark:text-white">
                        {{ $limits['remaining_tab_spaces'] }} of {{ $limits['tab_spaces'] }} remaining
                    </span>
                </div>
            @else
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tab Spaces:</span>
                    <span class="text-sm text-gray-900 dark:text-white">Unlimited</span>
                </div>
            @endif
            
            @if($limits['tournaments_per_tab'] !== -1)
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tournaments per Tab:</span>
                    <span class="text-sm text-gray-900 dark:text-white">
                        {{ $limits['remaining_tournaments_per_tab'] }} of {{ $limits['tournaments_per_tab'] }} remaining
                    </span>
                </div>
            @else
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tournaments per Tab:</span>
                    <span class="text-sm text-gray-900 dark:text-white">Unlimited</span>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-4">
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">No active subscription</div>
            <div class="text-xs text-gray-400 dark:text-gray-500">Contact an administrator to get a subscription</div>
        </div>
    @endif
</div> 