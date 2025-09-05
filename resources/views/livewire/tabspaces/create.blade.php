<div>
    <div class="mx-auto py-4">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:input-label for="name" value="Name" />
                <flux:text-input wire:model="name" id="name" class="mt-1 block w-full" type="text" required autofocus />
                <flux:input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <flux:input-label for="context" value="Context" />
                <flux:textarea wire:model="context" id="context" class="mt-1 block w-full" rows="6" required />
                <flux:input-error :messages="$errors->get('context')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <flux:checkbox wire:model="is_public" id="is_public" />
                <flux:input-label for="is_public" value="Make this tabspace public" />
                <flux:input-error :messages="$errors->get('is_public')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <flux:button type="submit">Create Tabspace</flux:button>
                <flux:button href="{{ route('tabspaces.index') }}" variant="secondary">Cancel</flux:button>
            </div>
        </form>
    </div>
</div>
