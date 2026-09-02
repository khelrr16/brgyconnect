<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Blotter Records',
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Blotter Records
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Monitor incidents, cases, and scheduled hearings.
                </p>
            </div>

            <a
                href="{{ route('blotters.create') }}"
                class="inline-flex items-center gap-2
                    rounded-lg
                    border border-blue-700
                    bg-blue-600
                    px-4 py-2.5
                    text-sm font-semibold text-white
                    shadow-md
                    hover:bg-blue-700
                    hover:shadow-lg">

                <i class="fa-solid fa-plus"></i>
                New Record
            </a>
        </div>
    </x-slot>

    <livewire:blotters.blotter-list />

</x-app-layout>