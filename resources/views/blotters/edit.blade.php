<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Edit Blotter Record
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $blotter->blotter_number }}
                </p>
            </div>

        </div>

    </x-slot>


    <div class="min-h-screen bg-gray-50 py-8">

        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            <livewire:blotters.blotter-edit
                :blotter="$blotter"
            />

        </div>

    </div>

</x-app-layout>