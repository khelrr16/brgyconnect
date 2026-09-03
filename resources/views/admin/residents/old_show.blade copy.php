<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold uppercase text-xl text-gray-800">
                <a
                    href="{{ route('admin.residents.index') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-gray-200 p-2"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                Resident Profile
            </h2>
        </div>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Resident Information --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <div class="flex items-center justify-between mb-6">

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $resident->last_name }},
                            {{ $resident->first_name }}
                            {{ $resident->middle_name }}
                        </h1>

                        <p class="text-gray-500">
                            {{ $resident->resident_id }}
                        </p>
                    </div>

                    <a
                        href="{{ route('residents.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                        EDIT
                    </a>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <p class="text-sm text-gray-500">
                            Birthday
                        </p>

                        <p class="font-medium">
                            {{ $resident->birth_date->format('F j, Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Sex
                        </p>

                        <p class="font-medium">
                            {{ $resident->sex }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Contact Number
                        </p>

                        <p class="font-medium">
                            {{ $resident->contact_number ?: 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Address
                        </p>

                        <p class="font-medium">
                            {{ $resident->block ? 'Block ' . $resident->block : '' }}
                            {{ $resident->lot ? 'Lot ' . $resident->lot : '' }}
                            {{ $resident->unit ? 'Unit ' . $resident->unit : '' }}
                            {{ $resident->street ? $resident->street : '' }}
                            {{ $resident->subdivision ? $resident->subdivision : 'N/A' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Immunization --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <div class="flex justify-between items-center mb-4">

                    <h2 class="text-xl font-semibold">
                        Immunization Records
                    </h2>

                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        + Add Immunization
                    </button>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>
                            <tr class="border-b">
                                <th class="text-left px-4 py-3">
                                    Vaccine
                                </th>

                                <th class="text-left px-4 py-3">
                                    Date
                                </th>

                                <th class="text-left px-4 py-3">
                                    Dose
                                </th>

                                <th class="text-left px-4 py-3">
                                    Remarks
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- We'll connect this to the database later --}}

                            <tr>
                                <td
                                    colspan="4"
                                    class="text-center py-6 text-gray-500">
                                    No immunization records.
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Blotter Records --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <div class="flex justify-between items-center mb-4">

                    <h2 class="text-xl font-semibold">
                        Blotter Records
                    </h2>

                    <button
                        class="px-4 py-2 bg-red-600 text-white rounded-lg">
                        + Add Blotter Record
                    </button>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>
                            <tr class="border-b">
                                <th class="text-left px-4 py-3">
                                    Date
                                </th>

                                <th class="text-left px-4 py-3">
                                    Case
                                </th>

                                <th class="text-left px-4 py-3">
                                    Status
                                </th>

                                <th class="text-left px-4 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- We'll connect this to the database later --}}

                            <tr>
                                <td
                                    colspan="4"
                                    class="text-center py-6 text-gray-500">
                                    No blotter records.
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>