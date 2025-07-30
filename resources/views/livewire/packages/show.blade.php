<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('packages.index') }}" class="text-blue-600 hover:text-blue-900 mr-4">
                ← Back to Packages
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $package->name }}</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Package Details -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Package Details</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('packages.edit', $package) }}" 
                               class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                Edit
                            </a>
                            <button wire:click="deletePackage" 
                                    wire:confirm="Are you sure you want to delete this package?"
                                    class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $package->name }}</p>
                        </div>

                        @if($package->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $package->description }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($package->price, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Max Tab Spaces</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $package->max_tab_spaces }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Max Tournaments per Tab</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $package->max_tournaments_per_tab }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $package->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $package->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Stats -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Package Statistics</h3>
                    
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $package->subscriptions->count() }}</div>
                            <div class="text-sm text-gray-600">Total Subscriptions</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $package->subscriptions->where('status', 'active')->count() }}</div>
                            <div class="text-sm text-gray-600">Active Subscriptions</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $package->subscriptions->where('status', 'expired')->count() }}</div>
                            <div class="text-sm text-gray-600">Expired Subscriptions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Subscriptions -->
        <div class="mt-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Subscriptions</h3>
                </div>
                
                @if($package->subscriptions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($package->subscriptions->take(5) as $subscription)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $subscription->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($subscription->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $subscription->start_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $subscription->end_date->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-4 text-center text-gray-500">
                        No subscriptions found for this package.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 