<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">

        <!-- Heading -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Teams for {{ $tournament->name }}
            </h2>

            <!-- Button: Open Create Modal -->
            <div class="flex justify-between items-center space-x-2  mb-6">
                <flux:modal.trigger name="create-team-modal">
                    <flux:button icon="plus">Create Team</flux:button>
                </flux:modal.trigger>

                @livewire('tournaments.teams.ai-generate', ['tournament' => $tournament], key('ai-generate-' . $tournament->id))
            </div>

        </div>

        <!-- Teams List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tournament->teams as $team)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                            {{ $team->name }}
                        </h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $team->members->count() }} Members
                        </span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-2">
                        Category: {{ $team->participantCategory?->name ?? '—' }}
                    </p>

                    @if ($team->institution?->institution)
                        <p class="text-gray-600 dark:text-gray-300 mb-2">
                            Institution: {{ $team->institution->institution->name }}
                        </p>
                    @endif

                    <div class="mt-auto flex space-x-2">
                        <flux:button size="sm" wire:click="manageMembers({{ $team->id }})" icon="users">
                            Members
                        </flux:button>

                        <!-- Edit Button -->
                        <flux:button size="sm" wire:click="editTeam({{ $team->id }})" icon="pencil-square">Edit
                        </flux:button>

                        <!-- Delete Button -->
                        <flux:button size="sm" wire:click="confirmDeleteTeam({{ $team->id }})" variant="danger"
                            icon="trash">Delete</flux:button>

                    </div>
                </div>
            @empty
                <p class="col-span-full text-gray-500 dark:text-gray-400 text-center">
                    No teams created yet.
                </p>
            @endforelse
        </div>

        <!-- Create Modal -->
        <flux:modal variant="flyout" name="create-team-modal" title="Create New Team">
            @include('livewire.tournaments.teams.partials.form', [
                'mode' => 'create',
                'action' => 'createTeam',
            ])
        </flux:modal>

        <!-- Edit Modal -->
        <flux:modal variant="flyout" name="edit-team-modal" title="Edit Team">
            @include('livewire.tournaments.teams.partials.form', [
                'mode' => 'edit',
                'action' => 'updateTeam',
            ])
        </flux:modal>

        <!-- Delete Confirmation Modal -->
        <flux:modal name="delete-team-modal" title="Delete Team">
            <div class="space-y-4">
                <p class="text-gray-700 dark:text-gray-200">
                    Are you sure you want to delete the team
                    <span class="font-semibold">{{ $deleteTeamName }}</span>?
                </p>
                <div class="flex justify-end space-x-2">
                    <flux:button variant="outline" icon="x-mark" wire:click="closeModal">Cancel</flux:button>
                    <flux:button variant="danger" icon="trash" wire:click="deleteTeam">Delete</flux:button>
                </div>
            </div>
        </flux:modal>



        <flux:modal variant="flyout" name="manage-members-modal" title="Manage Team Members">
            <div class="space-y-4">
                <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                    {{ $managingTeam?->name }}
                </h3>

                <!-- Members List -->
                <div class="space-y-2">
                    {{-- @dd($teamMembers) --}}
                    @forelse($teamMembers as $member)
                        <div
                            class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <!-- Left: Name & role -->
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $member->debater->participant->name }}
                                    @if ($member->debater->nickname)
                                        <span
                                            class="text-gray-500 dark:text-gray-400">({{ $member->debater->nickname }})</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Role: <span class="font-semibold">{{ ucfirst($member->role ?? '—') }}</span>
                                </p>
                            </div>

                            <!-- Right: Action -->
                            <flux:button size="xs" variant="danger" wire:click="removeMember({{ $member->id }})"
                                icon="trash">
                                Remove
                            </flux:button>
                        </div>

                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No members yet.</p>
                    @endforelse
                </div>

                <flux:separator text="or add a new member" />

                <!-- Add Member Form -->
                <div class="space-y-2">
                    <flux:select label="Select Debater" wire:model="newMemberDebater">
                        <option value="">-- Choose Debater --</option>
                        @foreach ($availableDebaters as $debaterId => $debaterName)
                            <flux:select.option value="{{ $debaterId }}">
                                {{ $debaterName }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:input label="Role" type="text" wire:model="newMemberRole" />

                    <flux:button wire:click="addMember" icon="plus">Add Member</flux:button>
                </div>
            </div>
        </flux:modal>

    </div>


</div>
