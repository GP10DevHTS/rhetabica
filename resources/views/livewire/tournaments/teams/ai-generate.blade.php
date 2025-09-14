<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div>
        <flux:modal.trigger name="generate-teams-modal">
            <flux:button icon="sparkles">Generate Teams</flux:button>
        </flux:modal.trigger>

        <flux:modal variant="flyout" name="generate-teams-modal" title="Generate Teams">
            <div class="space-y-4">

                <flux:heading>Use AI Power-up to generate teams</flux:heading>
                <p class="text-gray-700 dark:text-gray-200">
                    This will automatically generate teams based on the available debaters.<br>
                    Existing teams will not be affected.
                </p>


                <div class="flex space-x-2 gap-2">
                    <flux:button variant="primary" icon="sparkles" wire:click="generateTeams" size="sm">
                        1 Team per institution
                    </flux:button>


                    <flux:button variant="primary" icon="sparkles" wire:click="generateTeams" size="sm">
                        1 Team per Category
                    </flux:button>

                    <flux:button variant="primary" icon="sparkles" wire:click="generateTeams" size="sm">
                        1 Team per institution per category
                    </flux:button>
                </div>

                <flux:separator text="or" />

                <!-- Institution (optional) -->
                <div>
                    <flux:select label="Institution (optional)" wire:model="selectedInstitution"
                        placeholder="Choose Institution...">
                        <flux:select.option value="">None</flux:select.option>
                        @foreach ($institutions as $id => $name)
                            <flux:select.option value="{{ $id }}">{{ $name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Category (optional) -->
                <div>
                    <flux:select label="Category (optional)" wire:model="selectedCategory"
                        placeholder="Choose Category...">
                        <flux:select.option value="">None</flux:select.option>
                        @foreach ($categories as $id => $name)
                            <flux:select.option value="{{ $id }}">{{ $name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Exclude Existing Names -->
                <div>
                    <flux:textarea disabled label="Exclude Names (optional)" wire:model="excludedNames"
                        description="Comma-separated names to avoid..."></flux:textarea>
                </div>

                {{-- <!-- Number of Teams to Generate -->
                <div class="flex space-x-4">
                    <flux:input type="number" label="Number of Teams" wire:model="quantity" min="1"
                        max="20" value="5" description="Default: 5 teams"></flux:input>

                    <flux:input type="number" label="Teams per Institution" wire:model="teamsPerInstitution"
                        min="1" max="5" value="1"
                        description="Number of teams to generate per selected institution">
                    </flux:input>
                </div> --}}




                <!-- Tone / Style (optional) -->
                <div>
                    <flux:select label="Style (optional)" wire:model="style">
                        <flux:select.option value="">Neutral</flux:select.option>
                        <flux:select.option value="creative">Creative</flux:select.option>
                        <flux:select.option value="aggressive">Aggressive</flux:select.option>
                        <flux:select.option value="funny">Funny</flux:select.option>
                    </flux:select>
                </div>

                <!-- Generated Teams Preview -->
                @if ($generatedTeams)
                    <div class="mt-4 space-y-2">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-200">Generated Teams:</h3>
                        <ul class="list-disc list-inside text-gray-600 dark:text-gray-300">
                            @foreach ($generatedTeams as $team)
                                <li>{{ $team }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-2">
                    <flux:button variant="outline" icon="x-mark" wire:click="closeModal">Cancel</flux:button>
                    <flux:button variant="primary" icon="sparkles" wire:click="generateTeams">Generate Teams
                    </flux:button>
                </div>

               

            </div>
        </flux:modal>
    </div>

</div>
