<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Immunization Records',
                    'url' => route('admin.immunizations.index'),
                ],
                [
                    'label' => $immunization->full_name,
                    'url' => route('admin.immunizations.show', $immunization),
                ],
                [
                    'label' => 'Edit Record',
                ],
            ]"
        />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Immunization Record</h1>
            <p class="text-sm text-gray-500">Update vaccine dates for {{ $immunization->full_name }}</p>
        </div>
    </x-slot>

    <livewire:immunizations.immunization-edit :immunization="$immunization" />
</x-app-layout>
