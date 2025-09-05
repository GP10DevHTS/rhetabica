<div>
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-xl font-semibold mb-4">Participants</h2>
            <p class="mb-6">Manage the participants of your tournament.</p>
        </div>

        {{-- Search --}}
        <div class="w-1/3">
            <input type="text" placeholder="Search participants..." class="border p-2 rounded w-full"
                wire:model.live.debounce="search">
        </div>

        {{-- Add Participant --}}
        <div>
            <flux:modal.trigger name="add-participant">
                <flux:button icon="plus">Add participant</flux:button>
            </flux:modal.trigger>

            <flux:modal name="add-participant" variant="flyout">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">New participant</flux:heading>
                        <flux:text class="mt-2">Provide the details of the new participant</flux:text>
                    </div>

                    {{-- Name --}}
                    <flux:input label="Name" badge="Required" placeholder="Participant Name" wire:model="name" />

                    {{-- Email --}}
                    <flux:field>
                        <div class="flex space-x-2 justify-between">
                            <flux:label>Email</flux:label>
                            <flux:button variant="primary" size="sm" icon="arrow-up-on-square-stack" color="cyan"
                                wire:click="autoGenerateEmail">Auto Generate</flux:button>
                        </div>
                        <flux:input wire:model="email" type="email" placeholder="Participant Email" />
                        <flux:error name="email" />
                    </flux:field>

                    {{-- Phone --}}
                    <flux:input label="Phone" placeholder="Participant Phone Number" wire:model="phone" />

                    {{-- Gender --}}
                    <flux:select label="Gender" wire:model="gender" placeholder="Choose gender...">
                        <flux:select.option value="female">Female</flux:select.option>
                        <flux:select.option value="male">Male</flux:select.option>
                    </flux:select>

                    {{-- Role --}}
                    <flux:select label="Role" badge="Required" wire:model.live="role" placeholder="Choose role...">
                        <flux:select.option>Debater</flux:select.option>
                        <flux:select.option>Patron</flux:select.option>
                        <flux:select.option>Judge</flux:select.option>
                        <flux:select.option>Tab Master</flux:select.option>
                    </flux:select>

                    {{-- Institution --}}
                    <flux:select label="Institution" badge="Required" wire:model="institution"
                        placeholder="Choose Institution...">
                        @foreach ($institutions as $inst)
                            <flux:select.option value="{{ $inst->id }}">{{ $inst->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    {{-- Nickname --}}
                    <flux:input label="Nickname" placeholder="Participant Nickname" wire:model="nickname" />

                    {{-- Debater Category --}}
                    @if ($role === 'Debater')
                        <flux:select label="Debater Category" badge="Required" wire:model="participantCategory"
                            placeholder="Choose category...">
                            @foreach ($participantCategories as $category)
                                <flux:select.option value="{{ $category->id }}">{{ $category->name }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    @endif

                    {{-- Save --}}
                    <div class="flex">
                        <flux:spacer />
                        <flux:button type="button" wire:click="storeParticipant" variant="primary">Save</flux:button>
                    </div>
                </div>
            </flux:modal>
        </div>
    </div>

    <div class="space-y-8">

        {{-- Debaters --}}
        @if ($debaters && count($debaters) > 0)
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-100 dark:text-gray-200">Debaters</h3>
                @foreach ($debaters as $category => $debaterGroup)
                    <div class="mb-6">
                        <h4 class="font-semibold mb-2 text-gray-200 dark:text-gray-300">{{ $category }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($debaterGroup as $debater)
                                <div
                                    class="p-4 border rounded shadow-sm bg-white dark:bg-gray-800 dark:border-gray-700">
                                    <h5 class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $debater->participant->name }}</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Nickname:
                                        {{ $debater->nickname ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Category:
                                        {{ $debater->participantCategory?->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Institution:
                                        {{ $debater->institution->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email:
                                        {{ $debater->participant->email ?? '-' }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Phone:
                                        {{ $debater->participant->phone ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Judges --}}
        @if ($judges && count($judges) > 0)
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-100 dark:text-gray-200">Judges</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($judges as $judge)
                        <div class="p-4 border rounded shadow-sm bg-white dark:bg-gray-800 dark:border-gray-700">
                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $judge->participant->name }}
                            </h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Institution:
                                {{ $judge->institution->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Email:
                                {{ $judge->participant->email ?? '-' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Phone:
                                {{ $judge->participant->phone ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Patrons --}}
        @if ($patrons && count($patrons) > 0)
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-100 dark:text-gray-200">Patrons</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($patrons as $patron)
                        <div class="p-4 border rounded shadow-sm bg-white dark:bg-gray-800 dark:border-gray-700">
                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $patron->participant->name }}
                            </h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Institution:
                                {{ $patron->institution->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Email:
                                {{ $patron->participant->email ?? '-' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Phone:
                                {{ $patron->participant->phone ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tab Masters --}}
        @if ($tabMasters && count($tabMasters) > 0)
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-100 dark:text-gray-200">Tab Masters</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($tabMasters as $tabMaster)
                        <div class="p-4 border rounded shadow-sm bg-white dark:bg-gray-800 dark:border-gray-700">
                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $tabMaster->participant->name }}
                            </h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Institution:
                                {{ $tabMaster->institution->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Email:
                                {{ $tabMaster->participant->email ?? '-' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Phone:
                                {{ $tabMaster->participant->phone ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</div>
