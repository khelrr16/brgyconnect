<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Residents',
                    'url' => route('admin.residents.index'),
                ],
                [
                    'label' => $resident->resident_id,
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit Resident
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Update this resident's information.
                </p>
            </div>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <livewire:residents.resident-form :resident="$resident" />
            </div>
        </div>
    </div>
</x-app-layout>