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
                ],
            ]"
        />

                <div class="mb-6 flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Immunization Record
                </h1>

                    <p class="text-sm text-gray-500">
                    Infant immunization and health monitoring record
                </p>
            </div>

                <a
                    href="{{ route('admin.immunizations.edit', $immunization) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition"
                >
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit record
                </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">

        {{-- =========================================================
            INFANT SUMMARY
        ========================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">

            <div class="p-6">

                <div class="flex flex-col md:flex-row md:items-center gap-5">

                    {{-- Infant Details --}}
                    <div class="flex-1">

                        <div class="flex flex-wrap items-center gap-2">

                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ $immunization->full_name }}
                            </h2>

                            @if($immunization->low_birth_weight)

                                <span
                                    class="inline-flex items-center gap-1
                                        px-2.5 py-1 rounded-full
                                        bg-orange-100 text-orange-700
                                        text-xs font-semibold"
                                >
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    Low Birth Weight
                                </span>

                            @endif

                        </div>


                        <div class="flex flex-wrap gap-x-5 gap-y-2 mt-2 text-sm text-gray-500">

                            <span>
                                <i class="fa-solid fa-calendar-days mr-1"></i>

                                Born:
                                {{ $immunization->birthday?->format('F d, Y') ?? '—' }}
                            </span>

                            <span>
                                <i class="fa-solid fa-venus-mars mr-1"></i>

                                {{ ucfirst($immunization->sex) }}
                            </span>

                            <span>
                                <i class="fa-solid fa-cake-candles mr-1"></i>

                                {{ $immunization->age }}
                            </span>

                        </div>

                    </div>


                    {{-- Completion Status --}}
                    <div class="md:text-right">

                        @if($immunization->cic_date)

                            <span
                                class="inline-flex items-center gap-2
                                    px-3 py-2 rounded-lg
                                    bg-green-100 text-green-700
                                    text-sm font-semibold"
                            >
                                <i class="fa-solid fa-circle-check"></i>
                                Completely Immunized (CIC)
                            </span>

                        @elseif($immunization->fic_date)

                            <span
                                class="inline-flex items-center gap-2
                                    px-3 py-2 rounded-lg
                                    bg-yellow-100 text-yellow-700
                                    text-sm font-semibold"
                            >
                                <i class="fa-solid fa-clock"></i>
                                Fully Immunized (FIC)
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            PARENT INFORMATION
        ========================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600
                                flex items-center justify-center">

                        <i class="fa-solid fa-user"></i>

                    </div>

                    <div>
                        <h2 class="font-semibold text-gray-900">
                            Parent / Guardian
                        </h2>

                        <p class="text-xs text-gray-500">
                            Parent information and household address
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Parent --}}
                    <div>

                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Parent / Guardian
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $immunization->parent_first_name }}

                            @if($immunization->parent_middle_name)
                                {{ $immunization->parent_middle_name }}
                            @endif

                            {{ $immunization->parent_last_name }}
                        </p>

                        @if($immunization->resident)

                            <p class="text-xs text-green-600 mt-1">
                                <i class="fa-solid fa-link mr-1"></i>
                                Linked to resident record
                            </p>

                        @else

                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fa-solid fa-user-slash mr-1"></i>
                                Not linked to resident record
                            </p>

                        @endif

                    </div>


                    {{-- Address --}}
                    <div>

                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Address
                        </p>

                        <p class="mt-1 text-sm text-gray-900">

                            @php
                                $address = collect([
                                    $immunization->block ? 'Block ' . $immunization->block : null,
                                    $immunization->lot ? 'Lot ' . $immunization->lot : null,
                                    $immunization->unit ? 'Unit ' . $immunization->unit : null,
                                    $immunization->street,
                                    $immunization->subdivision,
                                ])->filter()->implode(', ');
                            @endphp

                            {{ $address ?: 'No address provided' }}

                        </p>

                    </div>

                </div>

            </div>

        </div>

        <div x-data="{ openSection: 'immunization', activeAgeTab: 'newborn' }" class="space-y-3">
        
            {{-- IMMUNIZATION --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden">

                <button
                    type="button"
                    @click="openSection = openSection === 'immunization' ? null : 'immunization'"
                    class="w-full flex items-center justify-between px-5 py-4
                        bg-white hover:bg-gray-50 transition"
                >

                    <div class="flex items-center gap-3">

                        <span class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600
                                    flex items-center justify-center">

                            <i class="fa-solid fa-syringe"></i>

                        </span>

                        <div class="text-left">

                            <p class="font-semibold text-gray-900">
                                Immunization
                            </p>

                            <p class="text-xs text-gray-500">
                                Vaccine doses and immunization schedule
                            </p>

                        </div>

                    </div>

                    <i
                        class="fa-solid fa-chevron-down text-gray-400 transition-transform"
                        :class="{ 'rotate-180': openSection === 'immunization' }"
                    ></i>

                </button>


                <div
                    x-show="openSection === 'immunization'"
                    x-collapse
                    x-cloak
                    class="border-t border-gray-200"
                >

                    <div class="p-5">

                        <div
                            class="grid grid-cols-2 md:grid-cols-4 gap-1 p-1 mb-6 bg-gray-100 rounded-lg"
                            role="tablist"
                            aria-label="Immunization schedule by age"
                        >
                            <button
                                type="button"
                                role="tab"
                                id="tab-newborn"
                                aria-controls="panel-newborn"
                                :aria-selected="activeAgeTab === 'newborn'"
                                @click="activeAgeTab = 'newborn'"
                                :class="activeAgeTab === 'newborn' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-3 py-2 text-xs sm:text-sm font-semibold rounded-md transition"
                            >
                                Newborn
                            </button>
                            <button
                                type="button"
                                role="tab"
                                id="tab-1-3-months"
                                aria-controls="panel-1-3-months"
                                :aria-selected="activeAgeTab === '1-3-months'"
                                @click="activeAgeTab = '1-3-months'"
                                :class="activeAgeTab === '1-3-months' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-3 py-2 text-xs sm:text-sm font-semibold rounded-md transition"
                            >
                                1–3 Months
                            </button>
                            <button
                                type="button"
                                role="tab"
                                id="tab-6-11-months"
                                aria-controls="panel-6-11-months"
                                :aria-selected="activeAgeTab === '6-11-months'"
                                @click="activeAgeTab = '6-11-months'"
                                :class="activeAgeTab === '6-11-months' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-3 py-2 text-xs sm:text-sm font-semibold rounded-md transition"
                            >
                                6–11 Months
                            </button>
                            <button
                                type="button"
                                role="tab"
                                id="tab-12-months"
                                aria-controls="panel-12-months"
                                :aria-selected="activeAgeTab === '12-months'"
                                @click="activeAgeTab = '12-months'"
                                :class="activeAgeTab === '12-months' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-3 py-2 text-xs sm:text-sm font-semibold rounded-md transition"
                            >
                                12 Months
                            </button>
                        </div>

                        {{-- NEWBORN --}}
                        <div
                            x-show="activeAgeTab === 'newborn'"
                            x-cloak
                            id="panel-newborn"
                            role="tabpanel"
                            aria-labelledby="tab-newborn"
                            class="mb-8"
                        >

                            <div class="flex items-center gap-2 mb-4">

                                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600
                                            flex items-center justify-center">

                                    <i class="fa-solid fa-baby"></i>

                                </span>

                                <h3 class="font-semibold text-gray-900">
                                    Newborn
                                </h3>

                            </div>

                            <div class="border border-gray-200 rounded-xl overflow-hidden">

                                <div class="overflow-x-auto">

                                    <table class="w-full text-sm">

                                        <thead class="bg-gray-50 border-b border-gray-200">

                                            <tr>

                                                <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                                    Vaccine
                                                </th>

                                                <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                                    Dose 1
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-gray-200">

                                            @foreach([
                                                'BCG' => 1,
                                                'Hepa B-BD' => 1,
                                            ] as $vaccine => $doses)

                                                @php
                                                    $record = $immunization->vaccineDoses
                                                        ->where('vaccine', $vaccine)
                                                        ->where('dose_number', 1)
                                                        ->first();
                                                @endphp

                                                <tr class="hover:bg-gray-50">

                                                    {{-- Vaccine --}}
                                                    <td class="px-4 py-3 font-medium text-gray-900">
                                                        {{ $vaccine }}
                                                    </td>

                                                    {{-- Dose 1 --}}
                                                    <td class="px-4 py-3 text-center">

                                                        @if($record?->date_given)

                                                            <div class="inline-flex flex-col items-center">

                                                                <span class="inline-flex items-center gap-1
                                                                            text-green-600 font-semibold">

                                                                    <i class="fa-solid fa-circle-check"></i>

                                                                    Given

                                                                </span>

                                                                <span class="text-xs text-gray-500 mt-1">
                                                                    {{ $record->date_given->format('M d, Y') }}
                                                                </span>

                                                            </div>

                                                        @else

                                                            <span class="text-gray-400">
                                                                Not given
                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                        {{-- 1–3 MONTHS --}}
                        <div
                            x-show="activeAgeTab === '1-3-months'"
                            x-cloak
                            id="panel-1-3-months"
                            role="tabpanel"
                            aria-labelledby="tab-1-3-months"
                            class="mb-8"
                        >

                            <div class="flex items-center gap-2 mb-4">

                                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600
                                            flex items-center justify-center">

                                    <i class="fa-solid fa-calendar-days"></i>

                                </span>

                                <h3 class="font-semibold text-gray-900">
                                    1–3 Months Old
                                </h3>

                            </div>

                            <div class="border border-gray-200 rounded-xl overflow-hidden">

                                <div class="overflow-x-auto">

                                    <table class="w-full text-sm">

                                        <thead class="bg-gray-50 border-b border-gray-200">

                                            <tr>

                                                <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                                    Vaccine
                                                </th>

                                                <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                                    Dose 1
                                                </th>

                                                <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                                    Dose 2
                                                </th>

                                                <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                                    Dose 3
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-gray-200">

                                            @foreach([
                                                'DPT-HiB-HepB' => 3,
                                                'OPV' => 3,
                                                'PCV' => 3,
                                                'IPV' => 1,
                                            ] as $vaccine => $doses)

                                                <tr class="hover:bg-gray-50">

                                                    {{-- Vaccine --}}
                                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                                        {{ $vaccine }}
                                                    </td>

                                                    {{-- Doses --}}
                                                    @for($dose = 1; $dose <= 3; $dose++)

                                                        @php
                                                            $record = $immunization->vaccineDoses
                                                                ->where('vaccine', $vaccine)
                                                                ->where('dose_number', $dose)
                                                                ->first();
                                                        @endphp

                                                        <td class="px-4 py-3 text-center">

                                                            @if($dose <= $doses)

                                                                @if($record?->date_given)

                                                                    <div class="inline-flex flex-col items-center">

                                                                        <span class="inline-flex items-center gap-1
                                                                                    text-green-600 font-semibold">

                                                                            <i class="fa-solid fa-circle-check"></i>

                                                                            Given

                                                                        </span>

                                                                        <span class="text-xs text-gray-500 mt-1">
                                                                            {{ $record->date_given->format('M d, Y') }}
                                                                        </span>

                                                                    </div>

                                                                @else

                                                                    <span class="text-gray-400">
                                                                        Not given
                                                                    </span>

                                                                @endif

                                                            @else

                                                                <span class="text-gray-300">
                                                                    —
                                                                </span>

                                                            @endif

                                                        </td>

                                                    @endfor

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                        {{-- 6–11 MONTHS --}}
                        <div
                            x-show="activeAgeTab === '6-11-months'"
                            x-cloak
                            id="panel-6-11-months"
                            role="tabpanel"
                            aria-labelledby="tab-6-11-months"
                            class="mb-8"
                        >

                            <div class="flex items-center gap-2 mb-4">

                                <span class="w-8 h-8 rounded-lg bg-pink-100 text-pink-600
                                            flex items-center justify-center">

                                    <i class="fa-solid fa-baby-carriage"></i>

                                </span>

                                <h3 class="font-semibold text-gray-900">
                                    6–11 Months Old
                                </h3>

                            </div>

                            <div class="border border-gray-200 rounded-xl overflow-hidden">

                                <div class="overflow-x-auto">

                                    <table class="w-full text-sm">

                                        <thead class="bg-gray-50 border-b border-gray-200">

                                            <tr>

                                                <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                                    Vaccine
                                                </th>

                                                <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                                    Dose 1
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-gray-200">

                                            @foreach([
                                                'MMR' => 1,
                                                'IPV' => 1,
                                            ] as $vaccine => $doses)

                                                @php
                                                    $record = $immunization->vaccineDoses
                                                        ->where('vaccine', $vaccine)
                                                        ->where('dose_number', 1)
                                                        ->first();
                                                @endphp

                                                <tr class="hover:bg-gray-50">

                                                    {{-- Vaccine --}}
                                                    <td class="px-4 py-3 font-medium text-gray-900">
                                                        {{ $vaccine }}
                                                    </td>

                                                    {{-- Dose 1 --}}
                                                    <td class="px-4 py-3 text-center">

                                                        @if($record?->date_given)

                                                            <div class="inline-flex flex-col items-center">

                                                                <span class="inline-flex items-center gap-1
                                                                            text-green-600 font-semibold">

                                                                    <i class="fa-solid fa-circle-check"></i>

                                                                    Given

                                                                </span>

                                                                <span class="text-xs text-gray-500 mt-1">
                                                                    {{ $record->date_given->format('M d, Y') }}
                                                                </span>

                                                            </div>

                                                        @else

                                                            <span class="text-gray-400">
                                                                Not given
                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    {{-- 12 MONTHS --}}
                    <div
                        x-show="activeAgeTab === '12-months'"
                        x-cloak
                        id="panel-12-months"
                        role="tabpanel"
                        aria-labelledby="tab-12-months"
                    >

                        <div class="flex items-center gap-2 mb-4">

                            <span class="w-8 h-8 rounded-lg bg-green-100 text-green-600
                                        flex items-center justify-center">

                                <i class="fa-solid fa-child"></i>

                            </span>

                            <h3 class="font-semibold text-gray-900">
                                12 Months Old
                            </h3>

                        </div>

                        @php
                            $record = $immunization->vaccineDoses
                                ->where('vaccine', 'MMR')
                                ->where('dose_number', 2)
                                ->first();
                        @endphp

                        <div class="border border-gray-200 rounded-xl overflow-hidden">

                            <div class="overflow-x-auto">

                                <table class="w-full text-sm">

                                    <thead class="bg-gray-50 border-b border-gray-200">

                                        <tr>

                                            <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                                Vaccine
                                            </th>

                                            <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                                Dose 2
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr class="hover:bg-gray-50">

                                            {{-- Vaccine --}}
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                MMR
                                            </td>

                                            {{-- Dose 2 --}}
                                            <td class="px-4 py-3 text-center">

                                                @if($record?->date_given)

                                                    <div class="inline-flex flex-col items-center">

                                                        <span class="inline-flex items-center gap-1
                                                                    text-green-600 font-semibold">

                                                            <i class="fa-solid fa-circle-check"></i>

                                                            Given

                                                        </span>

                                                        <span class="text-xs text-gray-500 mt-1">
                                                            {{ $record->date_given->format('M d, Y') }}
                                                        </span>

                                                    </div>

                                                @else

                                                    <span class="text-gray-400">
                                                        Not given
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    </div>

                </div>

            </div>


            {{-- NUTRITIONAL ASSESSMENTS --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden">

                <button
                    type="button"
                    @click="openSection = openSection === 'nutrition' ? null : 'nutrition'"
                    class="w-full flex items-center justify-between px-5 py-4
                        bg-white hover:bg-gray-50 transition"
                >

                    <div class="flex items-center gap-3">

                        <span class="w-9 h-9 rounded-lg bg-orange-100 text-orange-600
                                    flex items-center justify-center">

                            <i class="fa-solid fa-weight-scale"></i>

                        </span>

                        <div class="text-left">

                            <p class="font-semibold text-gray-900">
                                Nutritional Assessments
                            </p>

                            <p class="text-xs text-gray-500">
                                Growth and nutritional status records
                            </p>

                        </div>

                    </div>

                    <i
                        class="fa-solid fa-chevron-down text-gray-400 transition-transform"
                        :class="{ 'rotate-180': openSection === 'nutrition' }"
                    ></i>

                </button>


                <div
                    x-show="openSection === 'nutrition'"
                    x-collapse
                    x-cloak
                    class="border-t border-gray-200"
                >

                    <div class="p-6">

                        @if($immunization->nutritionalAssessments->count())

                            <div class="overflow-x-auto">

                                <table class="w-full text-sm">

                                    <thead>

                                        <tr class="text-left text-xs uppercase tracking-wide text-gray-500 border-b">

                                            <th class="pb-3 pr-4">
                                                Stage
                                            </th>

                                            <th class="pb-3 pr-4">
                                                Age
                                            </th>

                                            <th class="pb-3 pr-4">
                                                Length
                                            </th>

                                            <th class="pb-3 pr-4">
                                                Weight
                                            </th>

                                            <th class="pb-3 pr-4">
                                                Status
                                            </th>

                                            <th class="pb-3">
                                                Assessment Date
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody class="divide-y divide-gray-100">

                                        @foreach($immunization->nutritionalAssessments as $assessment)

                                            <tr>

                                                <td class="py-3 pr-4 font-medium text-gray-900">
                                                    {{ str_replace('_', ' ', ucfirst($assessment->stage)) }}
                                                </td>

                                                <td class="py-3 pr-4 text-gray-600">
                                                    {{ $assessment->age_value }}
                                                    {{ $assessment->age_unit }}
                                                </td>

                                                <td class="py-3 pr-4 text-gray-600">
                                                    {{ $assessment->length_cm }} cm
                                                </td>

                                                <td class="py-3 pr-4 text-gray-600">
                                                    {{ $assessment->weight_kg }} kg
                                                </td>

                                                <td class="py-3 pr-4">

                                                    <span class="inline-flex px-2 py-1 rounded-full
                                                                bg-gray-100 text-gray-700 text-xs font-medium">
                                                        {{ strtoupper($assessment->status) }}
                                                    </span>

                                                </td>

                                                <td class="py-3 text-gray-600">
                                                    {{ $assessment->assessment_date?->format('M d, Y') ?? '—' }}
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="text-center py-8 text-gray-400">

                                <i class="fa-solid fa-weight-scale text-2xl mb-2"></i>

                                <p class="text-sm">
                                    No nutritional assessments recorded yet.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- INFANT NUTRITION --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden">

                <button
                    type="button"
                    @click="openSection = openSection === 'infantNutrition'
                        ? null
                        : 'infantNutrition'"
                    class="w-full flex items-center justify-between px-5 py-4
                        bg-white hover:bg-gray-50 transition"
                >

                    <div class="flex items-center gap-3">

                        <span class="w-9 h-9 rounded-lg bg-green-100 text-green-600
                                    flex items-center justify-center">

                            <i class="fa-solid fa-bowl-food"></i>

                        </span>

                        <div class="text-left">

                            <p class="font-semibold text-gray-900">
                                Infant Nutrition
                            </p>

                            <p class="text-xs text-gray-500">
                                Breastfeeding and complementary feeding
                            </p>

                        </div>

                    </div>

                    <i
                        class="fa-solid fa-chevron-down text-gray-400 transition-transform"
                        :class="{ 'rotate-180': openSection === 'infantNutrition' }"
                    ></i>

                </button>


                <div
                    x-show="openSection === 'infantNutrition'"
                    x-collapse
                    x-cloak
                    class="border-t border-gray-200"
                >

                    <div class="p-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Breastfeeding after birth --}}
                            <div class="border border-gray-200 rounded-xl p-4">

                                <p class="text-xs text-gray-500 uppercase font-medium">
                                    Breastfeeding Initiated After Birth
                                </p>

                                <p class="mt-2 text-sm font-semibold
                                    {{ $immunization->breastfeeding_initiated ? 'text-green-600' : 'text-gray-500' }}">

                                    @if($immunization->breastfeeding_initiated)

                                        <i class="fa-solid fa-circle-check mr-1"></i>
                                        Yes

                                        @if($immunization->breastfeeding_initiated_date)

                                            <span class="font-normal text-gray-500">
                                                · {{ $immunization->breastfeeding_initiated_date->format('M d, Y') }}
                                            </span>

                                        @endif

                                    @else

                                        <i class="fa-solid fa-circle-xmark mr-1"></i>
                                        No / Not recorded

                                    @endif

                                </p>

                            </div>


                            {{-- Exclusive breastfeeding --}}
                            <div class="border border-gray-200 rounded-xl p-4">

                                <p class="text-xs text-gray-500 uppercase font-medium">
                                    Exclusive Breastfeeding up to 5 Months & 29 Days
                                </p>

                                <p class="mt-2 text-sm font-semibold
                                    {{ $immunization->exclusive_bf_5m29d ? 'text-green-600' : 'text-gray-500' }}">

                                    @if($immunization->exclusive_bf_5m29d)

                                        <i class="fa-solid fa-circle-check mr-1"></i>
                                        Yes

                                        @if($immunization->exclusive_bf_date)

                                            <span class="font-normal text-gray-500">
                                                · {{ $immunization->exclusive_bf_date->format('M d, Y') }}
                                            </span>

                                        @endif

                                    @else

                                        <i class="fa-solid fa-circle-xmark mr-1"></i>
                                        No / Not recorded

                                    @endif

                                </p>

                            </div>


                            {{-- Complementary feeding --}}
                            <div class="border border-gray-200 rounded-xl p-4 md:col-span-2">

                                <p class="text-xs text-gray-500 uppercase font-medium">
                                    Complementary Feeding at 6 Months
                                </p>

                                <p class="mt-2 text-sm font-semibold text-gray-900">

                                    @if($immunization->complementary_feeding_status)

                                        {{ $immunization->complementary_feeding_status }}

                                    @else

                                        <span class="text-gray-400">
                                            Not recorded
                                        </span>

                                    @endif

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- VITAMIN A & MNP --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden">

                <button
                    type="button"
                    @click="openSection = openSection === 'vitaminMnp'
                        ? null
                        : 'vitaminMnp'"
                    class="w-full flex items-center justify-between px-5 py-4
                        bg-white hover:bg-gray-50 transition"
                >

                    <div class="flex items-center gap-3">

                        <span class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600
                                    flex items-center justify-center">

                            <i class="fa-solid fa-pills"></i>

                        </span>

                        <div class="text-left">

                            <p class="font-semibold text-gray-900">
                                Vitamin A & Micronutrient Powder
                            </p>

                            <p class="text-xs text-gray-500">
                                Vitamin A supplementation and MNP records
                            </p>

                        </div>

                    </div>

                    <i
                        class="fa-solid fa-chevron-down text-gray-400 transition-transform"
                        :class="{ 'rotate-180': openSection === 'vitaminMnp' }"
                    ></i>

                </button>


                <div
                    x-show="openSection === 'vitaminMnp'"
                    x-collapse
                    x-cloak
                    class="border-t border-gray-200"
                >

                    <div class="p-6">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            {{-- Vitamin A --}}
                            <div class="border border-gray-200 rounded-xl p-4">

                                <p class="text-xs text-gray-500 uppercase font-medium">
                                    Vitamin A
                                </p>

                                <p class="mt-2 text-sm font-semibold text-gray-900">

                                    @if($immunization->vitamin_a_date)

                                        {{ $immunization->vitamin_a_date->format('M d, Y') }}

                                    @else

                                        <span class="text-gray-400">
                                            Not given
                                        </span>

                                    @endif

                                </p>

                            </div>


                            {{-- MNP 90 sachets --}}
                            <div class="border border-gray-200 rounded-xl p-4">

                                <p class="text-xs text-gray-500 uppercase font-medium">
                                    MNP — 90 Sachets Given
                                </p>

                                <p class="mt-2 text-sm font-semibold text-gray-900">

                                    @if($immunization->mnp_90_sachets_date)

                                        {{ $immunization->mnp_90_sachets_date->format('M d, Y') }}

                                    @else

                                        <span class="text-gray-400">
                                            Not recorded
                                        </span>

                                    @endif

                                </p>

                            </div>


                            {{-- MNP completed --}}
                            <div class="border border-gray-200 rounded-xl p-4">

                                <p class="text-xs text-gray-500 uppercase font-medium">
                                    MNP Completed
                                </p>

                                <p class="mt-2 text-sm font-semibold text-gray-900">

                                    @if($immunization->mnp_completed_date)

                                        {{ $immunization->mnp_completed_date->format('M d, Y') }}

                                    @else

                                        <span class="text-gray-400">
                                            Not completed
                                        </span>

                                    @endif

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =========================================================
            FIC / CIC
        ========================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            <button
                type="button"
                @click="openSection = openSection === 'ficCic'
                    ? null
                    : 'ficCic'"
                class="w-full flex items-center justify-between px-6 py-4
                    bg-white hover:bg-gray-50 transition"
            >

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-lg bg-green-100 text-green-600
                                flex items-center justify-center">

                        <i class="fa-solid fa-shield-heart"></i>

                    </div>

                    <div class="text-left">

                        <h2 class="font-semibold text-gray-900">
                            Immunization Completion
                        </h2>

                        <p class="text-xs text-gray-500">
                            Fully and completely immunized status
                        </p>

                    </div>

                </div>

                <i
                    class="fa-solid fa-chevron-down text-gray-400 transition-transform"
                    :class="{ 'rotate-180': openSection === 'ficCic' }"
                ></i>

            </button>


            <div
                x-show="openSection === 'ficCic'"
                x-collapse
                x-cloak
                class="border-t border-gray-200"
            >

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- FIC --}}
                        <div class="border border-gray-200 rounded-xl p-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-lg bg-green-100
                                            text-green-600 flex items-center justify-center">

                                    <i class="fa-solid fa-circle-check"></i>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500 uppercase font-medium">
                                        Fully Immunized Child
                                    </p>

                                    <p class="text-lg font-semibold text-gray-900">

                                        {{ $immunization->fic_date?->format('F d, Y') ?? 'Not recorded' }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- CIC --}}
                        <div class="border border-gray-200 rounded-xl p-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-lg bg-blue-100
                                            text-blue-600 flex items-center justify-center">

                                    <i class="fa-solid fa-shield"></i>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500 uppercase font-medium">
                                        Completely Immunized Child
                                    </p>

                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $immunization->cic_date?->format('F d, Y') ?? 'Not recorded' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        </div>

    </div>

</x-app-layout>

