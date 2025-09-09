<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Institutions</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage the institutions invited to your tournament.
                </p>
            </div>

            {{-- Search --}}
            <div class="w-1/3">
                <flux:input type="text" placeholder="Search institution..." icon="magnifying-glass"
                    wire:model.live.debounce="search" class="w-full" />
            </div>

            {{-- Add Institution --}}
            <flux:modal.trigger name="add-invite">
                <flux:button icon="plus">Invite Institution</flux:button>
            </flux:modal.trigger>
        </div>

        {{-- Modal for new invitation --}}
        <flux:modal name="add-invite" variant="flyout">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Invite Institution</flux:heading>
                    <flux:text class="mt-2">Fill in the details to invite a new institution to the tournament.
                    </flux:text>
                </div>

                <form wire:submit.prevent="inviteInstitution" class="space-y-4">
                    
                    <flux:select label="Institution" placeholder="Select institution" wire:model="newInstitution">
                        @foreach ($institutions as $institution)
                            <flux:select.option value="{{ $institution->id }}">{{ $institution->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:input
                        label="New Institution Name"
                        placeholder="If not listed, enter a new institution name..."
                        wire:model="newInstitutionName"
                    />


                    <flux:textarea label="Notes" placeholder="Internal notes about this invitation..."
                        wire:model="invitationNotes" />

                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary" icon="check">Invite</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>

        {{-- List of invited institutions --}}
        <div class="mt-8 space-y-4">
            @forelse ($invitedInstitutions as $invite)
                <div class="p-4 flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                            {{ $invite->institution->name ?? $invite->name_override }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $invite->invitation_notes ?? 'No notes provided' }}
                        </p>
                    </div>

                    {{-- Progression buttons --}}
                    <div class="flex space-x-2">
                        @if (!$invite->invited_at)
                            <flux:button size="sm"
                                wire:click="markInvited({{ $invite->id }})">
                                Mark Invited
                            </flux:button>
                        @elseif(!$invite->confirmed_at)
                            <flux:button size="sm"
                                wire:click="markConfirmed({{ $invite->id }})">
                                Mark Confirmed
                            </flux:button>
                        @elseif(!$invite->arrived_at)
                            <flux:button size="sm"
                                wire:click="markArrived({{ $invite->id }})">
                                Mark Arrived
                            </flux:button>
                        @else
                            <flux:badge variant="success">Completed</flux:badge>
                        @endif
                    </div>
                </div>
            @empty
                <div icon="users" title="No invitations yet"
                    text="Start by inviting an institution to your tournament.">
                    <flux:modal.trigger name="add-invite">
                        <flux:button icon="plus">Invite Institution</flux:button>
                    </flux:modal.trigger>
                </div>
            @endforelse
        </div>
    </div>

</div>
