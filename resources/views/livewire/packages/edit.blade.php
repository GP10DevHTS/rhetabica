<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('packages.index') }}" class="text-blue-600 hover:text-blue-900 mr-4">
                ← Back to Packages
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Package</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form wire:submit="save">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                    <input type="text" wire:model="name" id="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" id="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"></textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                    <input type="number" wire:model="price" id="price" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror" 
                           required>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="max_tab_spaces" class="block text-sm font-medium text-gray-700 mb-2">Max Tab Spaces</label>
                        <input type="number" wire:model="max_tab_spaces" id="max_tab_spaces" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_tab_spaces') border-red-500 @enderror" 
                               required>
                        @error('max_tab_spaces')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_tournaments_per_tab" class="block text-sm font-medium text-gray-700 mb-2">Max Tournaments per Tab</label>
                        <input type="number" wire:model="max_tournaments_per_tab" id="max_tournaments_per_tab" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_tournaments_per_tab') border-red-500 @enderror" 
                               required>
                        @error('max_tournaments_per_tab')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active" 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active Package</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('packages.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 