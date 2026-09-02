<x-app-layout>

    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Blotter Records',
                    'url' => route('blotters.index'),
                ],
                [
                    'label' => 'New',
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    New Blotter Record
                </h2>
            </div>

            <a
                href="{{ route('blotters.create') }}"
                class="inline-flex items-center gap-2
                    rounded-lg
                    border border-indigo-700
                    bg-indigo-600
                    px-4 py-2.5
                    text-sm font-semibold text-white
                    shadow-md
                    hover:bg-indigo-700
                    hover:shadow-lg">

                <i class="fa-solid fa-plus"></i>
                Create Blotter
            </a>
        </div>
    </x-slot>

    <div class="py-12">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <livewire:blotters.blotter-form />

            </div>

        </div>

    </div>

</x-app-layout>