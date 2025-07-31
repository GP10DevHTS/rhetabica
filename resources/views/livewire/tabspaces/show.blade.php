<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class=" mx-auto py-4">

        <div class="flex justify-between mb-6">
            <div class="mt-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $tabspace->name }}
                </h1>

                <p class="mt-4 text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ $tabspace->context }}
                </p>
            </div>

            <div class="mt-6">
                <a href="{{ route('tabspaces.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    ← Back to Tabspaces
                </a>
            </div>
        </div>
    </div>

</div>
