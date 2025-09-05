<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class=" mx-auto py-4">

        <div class="flex justify-between mb-6">
            <div class="mt-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $tabspace->name }}
                </h1>

                <p class="mt-4 text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ $tabspace->context }}
                </p>
            </div>

            <div class="mt-6 space-x-4">
                @if(Auth::id() === $tabspace->user_id || Auth::user()->is_admin)
                    <flux:button wire:click="togglePublic" icon="{{ $tabspace->is_public ? 'eye-slash' : 'eye' }}">
                        {{ $tabspace->is_public ? 'Make Private' : 'Make Public' }}
                    </flux:button>
                @endif
                <flux:button href="{{ route('tabspaces.index') }}" icon="arrow-left">
                    Back to Tabspaces
                </flux:button>
            </div>
        </div>
    </div>

    <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-8">
        <livewire:tabspaces.tournament-list :tabspace="$tabspace" />
    </div>
</div>
