<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

                <a
                    href="{{ route('admin.households.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium
                           text-gray-500 hover:text-indigo-600"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Households
                </a>


                <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">

                        <div
                            class="flex h-14 w-14 items-center justify-center
                                   rounded-xl bg-indigo-50 text-indigo-600"
                        >
                            <i class="fa-solid fa-house text-xl"></i>
                        </div>

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                                Household
                            </p>

                            <h1 class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $household->household_id }}
                            </h1>

                        </div>

                    </div>


                    <span
                        class="inline-flex items-center gap-2 self-start
                               rounded-full bg-indigo-50 px-3 py-1.5
                               text-xs font-semibold text-indigo-700"
                    >
                        <i class="fa-solid fa-users"></i>
                        {{ $household->residents->count() }}
                        {{ Str::plural('Member', $household->residents->count()) }}
                    </span>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CONTENT --}}
        {{-- ================================================= --}}

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="grid gap-6 lg:grid-cols-3">


                {{-- ================================================= --}}
                {{-- ADDRESS --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white p-6 shadow-sm lg:col-span-1"
                >

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-lg bg-indigo-50 text-indigo-600"
                        >
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <h2 class="font-bold text-gray-900">
                            Address
                        </h2>

                    </div>


                    <div class="mt-5 space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <p class="text-xs text-gray-400">Block</p>
                                <p class="mt-1 text-sm font-medium text-gray-900">
                                    {{ $household->block ?: '—' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">Lot</p>
                                <p class="mt-1 text-sm font-medium text-gray-900">
                                    {{ $household->lot ?: '—' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">Unit</p>
                                <p class="mt-1 text-sm font-medium text-gray-900">
                                    {{ $household->unit ?: '—' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Subdivision</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $household->subdivision ?: '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-400">Street</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $household->street ?: '—' }}
                            </p>
                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MEMBERS --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white shadow-sm lg:col-span-2"
                >

                    <div
                        class="border-b border-gray-200 px-6 py-5"
                    >
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Household Members
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Residents assigned to this household.
                                </p>

                            </div>


                            {{-- Add Resident --}}
                            <a
                                href="{{ route('admin.residents.create', [$household]) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg
                                    bg-indigo-600 px-4 py-2.5 text-sm font-semibold
                                    text-white shadow-sm transition
                                    hover:bg-indigo-700"
                            >
                                <i class="fa-solid fa-user-plus"></i>
                                Add Resident
                            </a>

                        </div>

                    </div>


                    <div class="divide-y divide-gray-100">

                        @forelse($household->residents as $resident)

                            <div class="flex items-center gap-4 px-6 py-4">

                                {{-- Resident Avatar --}}
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center
                                        justify-center rounded-full
                                        bg-gray-100 font-semibold
                                        text-gray-600"
                                >
                                    {{ strtoupper(substr($resident->first_name ?? $resident->name ?? '?', 0, 1)) }}
                                </div>


                                {{-- Resident Information --}}
                                <div class="min-w-0 flex-1">

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $resident->full_name ?? 'Resident' }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $resident->resident_id ?? 'Resident' }}
                                    </p>

                                </div>


                                {{-- View Resident --}}
                                <a
                                    target="_blank"
                                    href="{{ route('admin.residents.show', $resident) }}"
                                    class="inline-flex shrink-0 items-center gap-2
                                        rounded-lg border border-gray-200
                                        bg-white px-3 py-2 text-xs
                                        font-semibold text-gray-700
                                        transition hover:border-indigo-200
                                        hover:bg-indigo-50 hover:text-indigo-600"
                                    title="View Resident"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                    <span class="hidden sm:inline">
                                        View
                                    </span>
                                </a>

                            </div>

                        @empty

                            <div class="px-6 py-12 text-center">

                                <i class="fa-solid fa-user-slash text-2xl text-gray-300"></i>

                                <p class="mt-3 text-sm text-gray-500">
                                    No residents are currently assigned to this household.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>