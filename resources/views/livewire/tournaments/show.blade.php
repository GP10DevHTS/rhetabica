<div>
    <div class="mx-auto py-4">
        <div class="flex justify-between mb-6">
            <div class="mt-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $tournament->name }}
                </h1>

                @if ($tournament->description)
                    <p class="mt-4 text-gray-700 dark:text-gray-300 whitespace-pre-line">
                        {{ $tournament->description }}
                    </p>
                @endif

                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Created by {{ $tournament->user->name }}
                </div>
            </div>

            <div class="mt-6 space-x-4">
                @if (Auth::id() === $tournament->user_id || Auth::user()->is_admin || $tournament->tabspace->user_id === Auth::id())
                    <flux:button wire:click="togglePublic" icon="{{ $tournament->is_public ? 'eye-slash' : 'eye' }}">
                        {{ $tournament->is_public ? 'Make Private' : 'Make Public' }}
                    </flux:button>
                @endif
                <flux:button href="{{ route('tabspaces.show', $tournament->tabspace->slug) }}" icon="arrow-left">
                    Back to Tabspace
                </flux:button>
            </div>
        </div>

        {{-- tab nav links --}}
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="flex space-x-8">
                <button wire:click="switchTab('overview')"
                    class="py-2 px-3 text-sm font-medium
                {{ $tab === 'overview' ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                    Overview
                </button>
                <button wire:click="switchTab('participants')"
                    class="py-2 px-3 text-sm font-medium
                {{ $tab === 'participants' ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                    Participants
                </button>
                <button wire:click="switchTab('institutions')"
                    class="py-2 px-3 text-sm font-medium
                {{ $tab === 'institutions' ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                    Institutions
                </button>
                <button wire:click="switchTab('teams')"
                    class="py-2 px-3 text-sm font-medium
                {{ $tab === 'teams' ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                    Teams
                </button>
            </nav>
        </div>

        {{-- loading indicator --}}
        <div wire:loading class="mb-4">
            <div class="flex items-center space-x-2 text-gray-500 dark:text-gray-400">
                <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span>Loading...</span>
            </div>
        </div>

        {{-- tab content --}}
        <div>
            @if ($tab === 'overview')
                @livewire('tournaments.overview', ['tournament' => $tournament], key('overview-'.$tournament->id))
            @elseif($tab === 'participants')
                @livewire('tournaments.participants.index', ['tournament' => $tournament], key('participants-'.$tournament->id))
            @elseif($tab === 'institutions')
                @livewire('tournaments.institutions.index', ['tournament' => $tournament], key('institutions-'.$tournament->id))
            @elseif($tab === 'teams')
                @livewire('tournaments.teams.index', ['tournament' => $tournament], key('teams-'.$tournament->id))
            @else
                <p>Select a tab to view content.</p>
            @endif
        </div>

    </div>
</div>
