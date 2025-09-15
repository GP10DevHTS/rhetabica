<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div>
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">

            <!-- Heading -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Rooms for {{ $tournament->name }}
                </h2>

                <div class="flex space-x-2">
                    <!-- Open Create / AI Modal -->
                    <flux:modal.trigger name="create-room-modal">
                        <flux:button icon="plus" icon:trailing="sparkles">
                            Create Room
                            <flux:badge icon="sparkles" color="lime">AI</flux:badge>
                        </flux:button>
                    </flux:modal.trigger>

                </div>
            </div>

            <!-- Rooms List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($rooms as $room)
                    <div id="room-{{ $room->uuid }}" wire:key="room-{{ $room->uuid }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                                {{ $room->name }}
                            </h3>
                            @if ($room->nickname)
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $room->nickname }}</span>
                            @endif
                        </div>

                        @if ($room->description)
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                {!! nl2br($room->description) !!}
                            </p>
                        @endif


                        <div class="mt-auto flex space-x-2">
                            <flux:button size="sm" wire:click="editRoom({{ $room->id }})" icon="pencil-square">
                                Edit
                            </flux:button>

                            <flux:button size="sm"
                                wire:click="confirmDeleteRoom({{ $room->id }}, '{{ $room->name }}')"
                                variant="danger" icon="trash">
                                Delete
                            </flux:button>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-gray-500 dark:text-gray-400 text-center">
                        No rooms created yet.
                    </p>
                @endforelse
            </div>

            <!-- Create Room + AI Modal -->
            <flux:modal variant="flyout" name="create-room-modal" title="Create New Room">
                <div class="space-y-4">

                    {{-- AI-Powered Generation First --}}
                    <flux:input type="number" label="Number of Rooms (AI-powered)" wire:model.defer="bulkRoomCount"
                        placeholder="e.g. 5" min="1" max="50" />

                    <div class="flex justify-end space-x-2">
                        <flux:button wire:click="generateRoomsWithAI" icon="sparkles" color="success">
                            Generate Rooms
                        </flux:button>
                    </div>

                    {{-- <flux:spacer /> --}}
                    <flux:separator class="my-4" text="or create a single room manually" />

                    {{-- Manual Creation --}}
                    <flux:input label="Room Name" wire:model.defer="name" placeholder="Enter room name" />
                    <flux:input label="Nickname" wire:model.defer="nickname" placeholder="Optional nickname" />
                    <flux:textarea rows="auto" resize="vertical" label="Description / Location" wire:model.defer="description" placeholder="Optional description or location" />
                    <div class="flex justify-end space-x-2">
                        <flux:button wire:click="closeModal" variant="outline" icon="x-mark">Cancel</flux:button>
                        <flux:button wire:click="createRoom" icon="plus">Create</flux:button>
                    </div>

                </div>
            </flux:modal>


            <!-- Edit Room Modal -->
            <flux:modal variant="flyout" name="edit-room-modal" title="Edit Room">
                <div class="space-y-4">
                    <flux:input label="Room Name" wire:model.defer="name" placeholder="Enter room name" />
                    <flux:input label="Nickname" wire:model.defer="nickname" placeholder="Optional nickname" />
                    <flux:textarea rows="auto" resize="vertical" label="Description / Location" wire:model.defer="description" placeholder="Optional description or location" />
                    <div class="flex justify-end space-x-2">
                        <flux:button wire:click="closeModal" variant="outline" icon="x-mark">Cancel</flux:button>
                        <flux:button wire:click="updateRoom" icon="pencil-square">Update</flux:button>
                    </div>
                </div>
            </flux:modal>

            <!-- Delete Room Confirmation Modal -->
            <flux:modal name="delete-room-modal" title="Delete Room">
                <div class="space-y-4">
                    <p class="text-gray-700 dark:text-gray-200">
                        Are you sure you want to delete the room
                        <span class="font-semibold">{{ $name }}</span>?
                    </p>

                    <div class="flex justify-end space-x-2">
                        <flux:button wire:click="closeModal" variant="outline" icon="x-mark">Cancel</flux:button>
                        <flux:button wire:click="deleteRoom({{ $editingId }})" variant="danger" icon="trash">Delete
                        </flux:button>
                    </div>
                </div>
            </flux:modal>

        </div>
    </div>

</div>
