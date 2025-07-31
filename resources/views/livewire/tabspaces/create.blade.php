<div>
    <section class="w-full">
        <x-settings.layout :heading="__('Create Tabspace')" :subheading="__('Create a new tabspace to organize your work.')">
            <form wire:submit="save" class="my-6 w-full space-y-6">
                @if (session('limit-reached'))
                    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        {{ session('limit-reached') }}
                    </div>
                @endif

                <flux:input wire:model="name" :label="__('Tabspace Name')" type="text" required autofocus autocomplete="off" />

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full">{{ __('Create') }}</flux:button>
                    </div>
                </div>
            </form>
        </x-settings.layout>
    </section>
</div>
