<div>
    <div class="mx-auto py-4">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:input-label for="name" value="Name" />
                <flux:text-input wire:model="name" id="name" class="mt-1 block w-full" type="text" required autofocus />
                <flux:input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <flux:input-label for="description" value="Description" />
                <flux:textarea wire:model="description" id="description" class="mt-1 block w-full" rows="6" />
                <flux:input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <flux:checkbox wire:model="is_public" id="is_public" />
                <flux:input-label for="is_public" value="Make this tournament public" />
                <flux:input-error :messages="$errors->get('is_public')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <flux:button type="submit">Save Changes</flux:button>
                <flux:button href="{{ route('tournaments.show', $tournament) }}" variant="secondary">Cancel</flux:button>
            </div>
        </form>
    </div>
</div>
