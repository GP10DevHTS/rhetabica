<div>
    <div class="w-full">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Tabspaces</h1>

            <div>
                <flux:modal.trigger name="create-tabspace-modal">
                    <flux:button variant="primary">Create Tabspace</flux:button>
                </flux:modal.trigger>

                <flux:modal name="create-tabspace-modal" variant="flyout" title="Create Tabspace">
                    <section class="w-full">
                        <form wire:submit="save" class="my-6 w-full space-y-6">
                            @if (session('limit-reached'))
                                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                    role="alert">
                                    {{ session('limit-reached') }}
                                </div>
                            @endif

                            <flux:input wire:model="name" :label="__('Tabspace Name')" type="text" required autofocus
                                autocomplete="off" />
                            <flux:textarea wire:model="context" :label="__('Tabspace Context')" type="text" required
                                autofocus autocomplete="off" />

                            <flux:field variant="inline">
                                <flux:checkbox wire:model="is_public" />
                                <flux:label>Make this tabspace public</flux:label>
                                <flux:error name="is_public" />
                            </flux:field>

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
        </div>

        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">
                                        Name</th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                        Visibility</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                @forelse ($tabspaces as $tabspace)
                                    <tr wire:key="tabspace-{{ $tabspace->slug }}" id="tabspace-{{ $tabspace->slug }}">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 dark:text-white sm:pl-6">
                                            <div class="font-semibold">{{ $tabspace->name }}</div>
                                            <div class="text-gray-500 dark:text-gray-400 text-sm">
                                                {{ \Illuminate\Support\Str::limit($tabspace->context, 50) }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            @if($tabspace->is_public)
                                                <flux:badge color="green">Public</flux:badge>
                                            @else
                                                <flux:badge color="gray">Private</flux:badge>
                                            @endif
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex space-x-2 justify-end">
                                                <flux:button wire:click="edit({{ $tabspace->id }})" icon="pencil">
                                                    Edit
                                                </flux:button>
                                                <flux:button href="{{ route('tabspaces.show', $tabspace->slug) }}"
                                                    icon:trailing="arrow-up-right">
                                                    Details
                                                </flux:button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                            No tabspaces found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <flux:modal name="edit-tabspace-modal" variant="flyout" title="Edit Tabspace">
            <section class="w-full">
                <form wire:submit="update" class="my-6 w-full space-y-6">
                    <flux:input wire:model="name" :label="__('Tabspace Name')" type="text" required autofocus
                        autocomplete="off" />

                    <flux:textarea wire:model="context" :label="__('Tabspace Context')" type="text" required
                        autofocus autocomplete="off" />

                    <flux:field variant="inline">
                        <flux:checkbox wire:model="is_public" />
                        <flux:label>Make this tabspace public</flux:label>
                        <flux:error name="is_public" />
                    </flux:field>

                    <flux:spacer />
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="w-full">{{ __('Update') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </section>
        </flux:modal>
    </div>
</div>
