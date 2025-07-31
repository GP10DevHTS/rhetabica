<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Subscription Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('subscriptions.edit', $subscription) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Subscription
                </a>
                <a href="{{ route('subscriptions.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to Subscriptions
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Subscription Information -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Subscription Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Information -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">User</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Name:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Email:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->user->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Package Information -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Package</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Name:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->package->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Price:</span>
                                <span class="text-sm text-gray-900 ml-2">${{ number_format($subscription->package->price, 2) }}</span>
                            </div>
                            @if($subscription->package->description)
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Description:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $subscription->package->description }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Status</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Current Status:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ml-2
                                    {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($subscription->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Is Active:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->isActive() ? 'Yes' : 'No' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Is Expired:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->isExpired() ? 'Yes' : 'No' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Date Information -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Dates</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Start Date:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->start_date->format('M d, Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">End Date:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->end_date->format('M d, Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Duration:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $subscription->start_date->diffForHumans($subscription->end_date, true) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extend Subscription -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Extend Subscription</h2>
            </div>
            <div class="p-6">
                <form wire:submit="extendSubscription">
                    <div class="flex items-end space-x-4">
                        <div class="flex-1">
                            <label for="days" class="block text-sm font-medium text-gray-700 mb-2">
                                Extend by (days)
                            </label>
                            <input type="number" wire:model="days" id="days" min="1" max="365"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('days') border-red-500 @enderror">
                            @error('days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded">
                            Extend Subscription
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                <h2 class="text-lg font-semibold text-red-900">Danger Zone</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Delete Subscription</h3>
                        <p class="text-sm text-gray-500">Once you delete a subscription, there is no going back. Please be certain.</p>
                    </div>
                    <button wire:click="deleteSubscription" 
                            wire:confirm="Are you sure you want to delete this subscription? This action cannot be undone."
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded">
                        Delete Subscription
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 