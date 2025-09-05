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
            </nav>
        </div>

        {{-- tab content --}}
        <div>
            @if ($tab === 'overview')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Overview</h2>
                    <p>{{ $tournament->description ?? 'No overview available.' }}</p>
                </div>
            @elseif($tab === 'participants')
                @livewire('tournaments.participants.index', ['tournament' => $tournament], key('participants-'.$tournament->id))
                
            @elseif($tab === 'institutions')
                <div>
                    <h2 class="text-xl font-semibold mb-4">Institutions</h2>
                    {{-- Loop institutions --}}
                </div>
            @endif
        </div>

    </div>
</div>
