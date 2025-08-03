<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between mb-6 gap-6">
            <div>
                <h2 class="text-2xl font-bold">Explore Public Tournaments</h2>
                <p class="text-gray-600">Browse through publicly shared tournaments</p>
            </div>

            <div>
                <flux:input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tournaments..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tournaments as $tournament)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="{{ route('tournaments.show', $tournament) }}"
                                class="text-indigo-600 hover:text-indigo-800">
                                {{ $tournament->name }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($tournament->description, 100) }}</p>
                        <div class="text-sm text-gray-500">
                            Created by {{ $tournament->user->name }} in
                            <a href="{{ route('tabspaces.show', $tournament->tabspace) }}"
                                class="text-indigo-600 hover:text-indigo-800">
                                {{ $tournament->tabspace->name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $tournaments->links() }}
        </div>
    </div>
</div>
