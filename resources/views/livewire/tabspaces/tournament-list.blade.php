<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Tournaments</h2>

        @if ($tabspace->user_id === auth()->id() || auth()->user()->is_admin)
            <div>
                <flux:modal.trigger name="create-tournament-modal">
                    <flux:button variant="primary">Create Tournament</flux:button>
                </flux:modal.trigger>

                <flux:modal name="create-tournament-modal" variant="flyout" title="Create Tournament">
                    <section class="w-full">
                        <form wire:submit="save" class="my-6 w-full space-y-6">
                            @if (session('tournament-limit-reached'))
                                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                    role="alert">
                                    {{ session('tournament-limit-reached') }}
                                </div>
                            @endif

                            <flux:input wire:model="name" :label="__('Tournament Name')" type="text" required
                                autofocus autocomplete="off" />
                            <flux:textarea wire:model="description" :label="__('Tournament Description')" type="text"
                                autofocus autocomplete="off" />

                            <flux:spacer />
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-end">
                                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}
                                    </flux:button>
                                </div>
                            </div>
                        </form>
                    </section>
                </flux:modal>
            </div>
        @endif
    </div>

    <div class="mt-6">
        @if ($tournaments->count() > 0)
            <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">
                                Name</th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                Visibility</th>
                            <th scope="col"
                                class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white lg:table-cell">
                                Created By</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @foreach ($tournaments as $tournament)
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 dark:text-white sm:pl-6">
                                    <div class="font-semibold">{{ $tournament->name }}</div>
                                    @if ($tournament->description)
                                        <div class="text-gray-500 dark:text-gray-400 text-sm">
                                            {{ \Illuminate\Support\Str::limit($tournament->description, 100) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    @if ($tournament->is_public)
                                        <flux:badge color="green">Public</flux:badge>
                                    @else
                                        <flux:badge color="gray">Private</flux:badge>
                                    @endif
                                </td>
                                <td
                                    class="hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">
                                    {{ $tournament->user->name }}
                                </td>
                                <td
                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex space-x-2 justify-end">
                                        <flux:button href="{{ route('tournaments.show', $tournament->slug) }}"
                                            icon:trailing="arrow-up-right">
                                            Details
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                No tournaments found in this tabspace.
            </div>
        @endif
    </div>
</div>
