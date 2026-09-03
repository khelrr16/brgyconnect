<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Households',
                    'url' => route('admin.households.index'),
                ],
                [
                    'label' => $household->household_id,
                    'url' => route('admin.households.show', $household->id),
                ],
                [
                    'label' => 'New Resident',
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    New Resident
                </h2>
            </div>
        </div>
    </x-slot>

    <livewire:residents.resident-form :household="$household" />

</x-app-layout>
