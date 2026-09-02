<x-app-layout>

    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Blotter Records',
                    'url' => route('blotters.index'),
                ],
                [
                    'label' => 'New Record',
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    New Blotter Record
                </h2>
            </div>
        </div>
    </x-slot>

    <livewire:blotters.blotter-form />

</x-app-layout>