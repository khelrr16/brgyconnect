<x-app-layout>
    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Households',
                ],
            ]"
        />

        <div class="mb-6 bg-white p-6 rounded-lg border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Households
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Manage household addresses and their members.
                </p>
            </div>

            <a
                href="{{ route('admin.households.create') }}"
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
                New Household
            </a>
        </div>

    </x-slot>


    <livewire:admin.households.household-list />

</x-app-layout>