<x-app-layout>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold uppercase text-xl text-gray-800">
                <a
                    href="{{ route('residents.index') }}"
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

            @php
                $display = fn ($value) => filled($value) ? $value : 'N/A';
            @endphp

            {{-- Resident Information --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <div class="flex items-center justify-between mb-6">

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $display($resident->last_name) }},
                            {{ $display($resident->first_name) }}
                            {{ $resident->middle_name }}
                            {{ $resident->extension_name }}
                        </h1>

                        <p class="text-gray-500">
                            {{ $resident->resident_id }}
                        </p>
                    </div>

                    <a
                        href="{{ route('residents.edit', $resident) }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                        EDIT
                    </a>

                </div>

                <div x-data="{ activeTab: 'personal' }">
                    <nav class="flex gap-2 overflow-x-auto border-b mb-6" aria-label="Resident information categories">
                        <button type="button" @click="activeTab = 'personal'" :class="activeTab === 'personal' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="inline-flex shrink-0 items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold">
                            <i class="fa-solid fa-id-card" aria-hidden="true"></i>
                            <span>Personal Identification</span>
                        </button>
                        <button type="button" @click="activeTab = 'residency'" :class="activeTab === 'residency' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="inline-flex shrink-0 items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold">
                            <i class="fa-solid fa-house" aria-hidden="true"></i>
                            <span>Residency Information</span>
                        </button>
                        <button type="button" @click="activeTab = 'socioeconomic'" :class="activeTab === 'socioeconomic' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="inline-flex shrink-0 items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold">
                            <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                            <span>Socio-Economic Data</span>
                        </button>
                        <button type="button" @click="activeTab = 'blotterrecords'" :class="activeTab === 'blotterrecords' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="inline-flex shrink-0 items-center gap-2 border-b-2 px-4 py-3 text-sm font-semibold">
                            <i class="fa-solid fa-stamp" aria-hidden="true"></i>
                            <span>Blotter Records</span>
                        </button>
                    </nav>

                    <section x-show="activeTab === 'personal'" x-cloak>
                        <h2 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">
                            Personal Identification
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div><p class="text-sm text-gray-500">First Name</p><p class="font-medium">{{ $display($resident->first_name) }}</p></div>
                            <div><p class="text-sm text-gray-500">Middle Name</p><p class="font-medium">{{ $display($resident->middle_name) }}</p></div>
                            <div><p class="text-sm text-gray-500">Last Name</p><p class="font-medium">{{ $display($resident->last_name) }}</p></div>
                            <div><p class="text-sm text-gray-500">Extension Name</p><p class="font-medium">{{ $display($resident->extension_name) }}</p></div>
                            <div><p class="text-sm text-gray-500">Sex</p><p class="font-medium">{{ $display($resident->sex) }}</p></div>
                            <div><p class="text-sm text-gray-500">Date of Birth</p><p class="font-medium">{{ $resident->birth_date ? $resident->birth_date->format('F j, Y') : 'N/A' }}</p></div>
                            <div><p class="text-sm text-gray-500">Civil Status</p><p class="font-medium">{{ $display($resident->civil_status) }}</p></div>
                            <div><p class="text-sm text-gray-500">Citizenship</p><p class="font-medium">{{ $display($resident->citizenship) }}</p></div>
                            <div><p class="text-sm text-gray-500">Place of Birth</p><p class="font-medium">{{ $display($resident->place_of_birth) }}</p></div>
                            <div><p class="text-sm text-gray-500">Contact Number</p><p class="font-medium">{{ $display($resident->contact_number) }}</p></div>
                            <div><p class="text-sm text-gray-500">Emergency Contact</p><p class="font-medium">{{ $display($resident->emergency_contact_name) }}</p></div>
                            <div><p class="text-sm text-gray-500">Emergency Contact Number</p><p class="font-medium">{{ $display($resident->emergency_contact_number) }}</p></div>
                            <div><p class="text-sm text-gray-500">Registered Voter</p><p class="font-medium">{{ $display($resident->registered_voter) }}</p></div>
                        </div>
                    </section>

                    <section x-show="activeTab === 'residency'" x-cloak>
                        <h2 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">
                            Residency Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div><p class="text-sm text-gray-500">Block</p><p class="font-medium">{{ $display($resident->block) }}</p></div>
                            <div><p class="text-sm text-gray-500">Lot</p><p class="font-medium">{{ $display($resident->lot) }}</p></div>
                            <div><p class="text-sm text-gray-500">Unit</p><p class="font-medium">{{ $display($resident->unit) }}</p></div>
                            <div><p class="text-sm text-gray-500">Street</p><p class="font-medium">{{ $display($resident->street) }}</p></div>
                            <div><p class="text-sm text-gray-500">Subdivision</p><p class="font-medium">{{ $display($resident->subdivision) }}</p></div>
                            <div><p class="text-sm text-gray-500">House Ownership</p><p class="font-medium">{{ $display($resident->house_ownership) }}</p></div>
                            <div><p class="text-sm text-gray-500">Relationship to Head</p><p class="font-medium">{{ $display($resident->relationship_to_head) }}</p></div>
                            <div><p class="text-sm text-gray-500">Residence Since</p><p class="font-medium">{{ $display($resident->residence_since) }}</p></div>
                        </div>
                    </section>

                    <section x-show="activeTab === 'socioeconomic'" x-cloak>
                        <h2 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">
                            Socio-Economic Data
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div><p class="text-sm text-gray-500">Educational Attainment</p><p class="font-medium">{{ $display($resident->educational_attainment) }}</p></div>
                            <div><p class="text-sm text-gray-500">Employment Status</p><p class="font-medium">{{ $display($resident->employment_status) }}</p></div>
                            <div><p class="text-sm text-gray-500">Religion</p><p class="font-medium">{{ $display($resident->religion) }}</p></div>
                            <div><p class="text-sm text-gray-500">Occupation</p><p class="font-medium">{{ $display($resident->occupation) }}</p></div>
                            <div><p class="text-sm text-gray-500">Monthly Income</p><p class="font-medium">{{ $resident->monthly_income !== null ? number_format((float) $resident->monthly_income, 2) : 'N/A' }}</p></div>
                        </div>
                    </section>

                    <section x-show="activeTab === 'blotterrecords'" x-cloak>
                        <h2 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">
                            Blotter Records
                        </h2>

                        @forelse ($resident->blotterRecords as $blotter)

                            <div class="border-b py-4">

                                <div class="flex justify-between">

                                    <div>

                                        <p class="font-semibold">
                                            {{ $blotter->blotter_number }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ $blotter->incident_type }}
                                        </p>

                                    </div>

                                    <a
                                        href="{{ route('blotters.show', $blotter) }}"
                                        class="text-blue-600"
                                    >
                                        View
                                    </a>

                                </div>

                            </div>

                        @empty

                            <p class="text-gray-500">
                                No blotter records found for this resident.
                            </p>

                        @endforelse
                        
                    </section>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>