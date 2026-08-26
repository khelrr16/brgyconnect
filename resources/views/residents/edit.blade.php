<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold uppercase text-xl text-gray-800">
            <a
                href="{{ route('residents.show', $resident) }}"
                class="inline-flex items-center justify-center rounded-lg bg-gray-200 p-2"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            Edit Resident
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <livewire:residents.resident-form :resident="$resident" />
            </div>
        </div>
    </div>
</x-app-layout>