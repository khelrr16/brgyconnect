<x-app-layout>

    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Immunization Records',
                    'url' => route('admin.immunizations.index'),
                ],
                [
                    'label' => 'New Record',
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    New Record
                </h2>
            </div>
        </div>
    </x-slot>

    <livewire:immunizations.immunization-form />

</x-app-layout>