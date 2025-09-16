<div>
    {{-- Stop trying to control. --}}
    <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
        <div class="max-w-7xl mx-auto">
            {{-- <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Tournament Overview: {{ $tournament->name }}
            </h1> --}}

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Institutions -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Institutions</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $tournament->institutions()->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-300">
                        Total invited institutions
                    </p>
                </div>

                {{-- <!-- Participants -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Participants</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $tournament->participants()->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-300">
                        Total registered participants
                    </p>
                </div> --}}

                <!-- Debaters -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Debaters</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $tournament->debaters()->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-300">
                        Total debaters competing
                    </p>
                </div>

                <!-- Judges -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Judges</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $tournament->judges()->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-300">
                        Judges assigned
                    </p>
                </div>

                <!-- Tab Masters -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tab Masters</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $tournament->tabMasters()->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-300">
                        Tournament administrators
                    </p>
                </div>

                <!-- Patrons -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Patrons</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $tournament->patrons()->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-300">
                        Sponsors & supporters
                    </p>
                </div>
            {{-- </div>

            {{-- Optional Stats / Progress --}=}
            <div class="mt-10 grid grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Arrived Institutions --> --}}
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Arrived Institutions
                    </h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $tournament->institutions()->whereNotNull('arrived_at')->count() }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Out of
                        {{ $tournament->institutions()->count() }} invited</p>
                </div>

            </div>
        </div>
    </div>

</div>
