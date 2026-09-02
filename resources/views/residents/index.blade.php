<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Residents',
                    'url' => route('residents.index'),
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Residents
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Manage registered residents.
                </p>
            </div>

            <a
                href="{{ route('residents.create') }}"
                class="inline-flex items-center gap-2
                       rounded-lg
                       border border-indigo-700
                       bg-indigo-600
                       px-4 py-2.5
                       text-sm font-semibold text-white
                       shadow-md
                       hover:bg-indigo-700
                       hover:shadow-lg"
            >
                <i class="fa-solid fa-plus"></i>
                New Resident
            </a>
        </div>

    </x-slot>

    <livewire:residents.resident-list />

</x-app-layout>