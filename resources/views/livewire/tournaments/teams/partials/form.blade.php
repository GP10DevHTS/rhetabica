<div>
    <!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
    <form wire:submit.prevent="{{ $action }}" class="space-y-4">

        <div>
            <flux:heading>
                {{ $mode === 'create' ? 'Create a new team for ' . $tournament->name : 'Edit team details' }}
            </flux:heading>
            <flux:subheading>
                {{ $mode === 'create' ? 'Fill in the details below and click "Save".' : 'Update the team details and click "Save".' }}
            </flux:subheading>
        </div>

        <div>
            <flux:input badge="required" label="Team Name" wire:model.defer="teamName"
                description="Name / Nickname of the team" placeholder="type name here" />
        </div>

        <div>
            <flux:select badge="optional" label="Participant Category" wire:model="participantCategory"
                description="Only this category shall be allowed on the team" placeholder="Choose Category...">
                @foreach ($participantCategories as $categoryid => $categoryname)
                    <flux:select.option value="{{ $categoryid }}">
                        {{ $categoryname }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div>
            <flux:select badge="optional" label="Institution" wire:model="tournamentInstitution"
                description="In case the team is affiliated to one" placeholder="Choose Institution...">
                @foreach ($tournament->institutions as $institution)
                    <flux:select.option value="{{ $institution->id }}">
                        {{ $institution->name_override ?? $institution->institution->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="outline" icon="x-mark" wire:click="closeModal">Cancel</flux:button>
            <flux:button type="submit" icon="check-badge">Save</flux:button>
        </div>
    </form>

</div>
