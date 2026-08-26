<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Blotter Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <livewire:blotters.blotter-list />

        </div>
    </div>

</x-app-layout>