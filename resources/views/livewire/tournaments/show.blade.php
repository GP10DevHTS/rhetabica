<div>
    <div class="mx-auto py-4">
        <div class="flex justify-between mb-6">
            <div class="mt-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $tournament->name }}
                </h1>

                @if($tournament->description)
                <p class="mt-4 text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ $tournament->description }}
                </p>
                @endif

                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Created by {{ $tournament->user->name }}
                </div>
            </div>

            <div class="mt-6">
                <flux:button href="{{ route('tabspaces.show', $tournament->tabspace->slug) }}" icon="arrow-left">
                    Back to Tabspace
                </flux:button>
            </div>
        </div>
    </div>
</div>
