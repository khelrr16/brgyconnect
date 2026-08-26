<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Blotter Record
                </h2>

                <p class="text-sm text-gray-500">
                    View incident and hearing information
                </p>
            </div>

            <a
                href="{{ route('blotters.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    </x-slot>


    <div  
        x-data
        x-on:hearing-scheduled.window="window.location.reload()"
        x-on:hearing-updated.window="window.location.reload()"
        class="min-h-screen py-8">

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            {{-- =========================================================
                 CASE HEADER
            ========================================================== --}}
            <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                <div class="grid grid-cols-1 gap-6 bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-6 text-white lg:grid-cols-2">

                    {{-- Details --}}
                    <div class="lg:row-span-2">
                        <p class="text-sm font-medium uppercase tracking-wider text-blue-100">
                            Barangay Blotter Record
                        </p>

                        <h1 class="mt-1 text-3xl font-bold">
                            {{ $blotter->blotter_number }}
                        </h1>

                        <p class="mt-2 text-blue-100">
                            {{ $blotter->incident_type }}
                        </p>
                    </div>


                    {{-- Status --}}
                    <div class="flex items-start justify-start lg:justify-end">

                        @php
                            $statusClass = match($blotter->status) {
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'Under Investigation' => 'bg-blue-100 text-blue-800',
                                'Settled' => 'bg-green-100 text-green-800',
                                'Referred' => 'bg-purple-100 text-purple-800',
                                'Closed' => 'bg-gray-100 text-gray-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp

                        <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                            <i class="fa-solid fa-circle text-[8px]"></i>
                            {{ $blotter->status }}
                        </span>

                    </div>


                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center justify-start gap-2 lg:justify-end">

                        {{-- Edit --}}
                        <a
                            href="{{ route('blotters.edit', $blotter) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm hover:bg-white/20"
                        >
                            <i class="fa-solid fa-pen"></i>
                            Edit
                        </a>


                        {{-- Schedule Hearing --}}
                        <livewire:blotters.schedule-hearing
                            :blotterId="$blotter->id"
                        />


                        {{-- Print --}}
                        <a
                            href="{{ route('blotters.print', $blotter) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm hover:bg-white/20"
                        >
                            <i class="fa-solid fa-print"></i>
                            Print
                        </a>

                    </div>

                </div>


                {{-- Quick information --}}
                <div class="grid grid-cols-2 border-b border-gray-200 sm:grid-cols-4">

                    <div class="border-r border-gray-200 p-5">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            Incident Date
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $blotter->incident_date->format('F j, Y') }}
                        </p>
                    </div>


                    <div class="border-b border-gray-200 p-5 sm:border-b-0 sm:border-r">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            Incident Time
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $blotter->incident_time
                                ? \Carbon\Carbon::parse($blotter->incident_time)->format('g:i A')
                                : 'N/A'
                            }}
                        </p>
                    </div>


                    <div class="border-r border-gray-200 p-5">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            Location
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $blotter->incident_location }}
                        </p>
                    </div>


                    <div class="p-5">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            Recorded By
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $blotter->creator?->name ?? 'N/A' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- =========================================================
                 MAIN CONTENT
            ========================================================== --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- =====================================================
                     LEFT / MAIN COLUMN
                ====================================================== --}}
                <div class="space-y-6 lg:col-span-2">


                    {{-- Incident Information --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="mb-5 flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                <i class="fa-solid fa-file-circle-exclamation"></i>
                            </div>

                            <div>
                                <h2 class="font-semibold text-gray-900">
                                    Incident Description
                                </h2>

                                <p class="text-gray-500">
                                    Details of the reported incident
                                </p>
                            </div>

                        </div>

                        <div class="rounded-xl bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                            {{ $blotter->incident_description }}
                        </div>


                        @if($blotter->action_taken)

                            <div class="mt-6">

                                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Action Taken
                                </p>

                                <div class="rounded-xl bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                                    {{ $blotter->action_taken }}
                                </div>

                            </div>

                        @endif


                        @if($blotter->remarks)

                            <div class="mt-6">

                                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Remarks
                                </p>

                                <div class="rounded-xl bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                                    {{ $blotter->remarks }}
                                </div>

                            </div>

                        @endif

                    </div>



                    {{-- People Involved --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="mb-5 flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                    <i class="fa-solid fa-users"></i>
                                </div>

                                <div>
                                    <h2 class="font-semibold text-gray-900">
                                        People Involved
                                    </h2>

                                    <p class="text-sm text-gray-500">
                                        Individuals associated with this case
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="space-y-3">

                            @forelse($blotter->parties as $party)

                                <div class="flex flex-col gap-4 rounded-xl border border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between">

                                    <div class="flex items-center gap-4">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-600">

                                            @if($party->role === 'Complainant')
                                                <i class="fa-solid fa-user-shield"></i>
                                            @elseif($party->role === 'Respondent')
                                                <i class="fa-solid fa-user"></i>
                                            @else
                                                <i class="fa-solid fa-user-group"></i>
                                            @endif

                                        </div>


                                        <div>

                                            <p class="font-semibold text-gray-900">

                                                {{ $party->last_name }},
                                                {{ $party->first_name }}
                                                {{ $party->middle_name }}

                                            </p>

                                            <div class="mt-1 flex flex-wrap items-center gap-2">

                                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                                    {{ $party->role }}
                                                </span>

                                                @if($party->resident_id)

                                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                                        Registered Resident
                                                    </span>

                                                @else

                                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                                        Unregistered
                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </div>


                                    <div class="text-sm text-gray-500 sm:text-right">

                                        <p>
                                            <i class="fa-solid fa-phone mr-1"></i>
                                            {{ $party->contact_number ?: 'No contact number' }}
                                        </p>

                                        <p class="mt-1">
                                            <i class="fa-solid fa-location-dot mr-1"></i>
                                            {{ $party->address ?: 'No address' }}
                                        </p>

                                    </div>

                                </div>

                            @empty

                                <p class="py-6 text-center text-sm text-gray-500">
                                    No people have been recorded.
                                </p>

                            @endforelse

                        </div>

                    </div>



                    {{-- Attachments --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

                        <div class="mb-5 flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                <i class="fa-solid fa-paperclip"></i>
                            </div>

                            <div>
                                <h2 class="font-semibold text-gray-900">
                                    Case Attachments
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Photos and supporting files
                                </p>
                            </div>

                        </div>


                        @if($blotter->attachments->count())

                            <div class="max-h-[420px] overflow-y-auto pr-2">

                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">

                                    @foreach($blotter->attachments as $attachment)

                                        <a
                                            href="{{ asset('storage/' . $attachment->file_path) }}"
                                            target="_blank"
                                            class="group overflow-hidden rounded-xl border border-gray-200"
                                        >

                                            <div class="aspect-square overflow-hidden bg-gray-100">

                                                <img
                                                    src="{{ asset('storage/' . $attachment->file_path) }}"
                                                    alt="{{ $attachment->original_name }}"
                                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                >

                                            </div>

                                            <div class="p-3">

                                                <p class="truncate text-sm font-medium text-gray-800">
                                                    {{ $attachment->original_name }}
                                                </p>

                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ number_format(($attachment->file_size ?? 0) / 1024, 1) }} KB
                                                </p>

                                            </div>

                                        </a>

                                    @endforeach

                                </div>

                            </div>

                        @else

                            <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center">

                                <i class="fa-solid fa-image text-3xl text-gray-400"></i>

                                <p class="mt-3 text-sm text-gray-500">
                                    No attachments have been uploaded.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>



                {{-- =====================================================
                     RIGHT SIDEBAR
                ====================================================== --}}
                <div class="space-y-6">


                    {{-- Next Hearing --}}
                    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-4 text-white">

                            <div class="flex items-center gap-3">

                                <i class="fa-solid fa-calendar-days text-lg"></i>

                                <div>
                                    <h2 class="font-semibold">
                                        Scheduled Hearing
                                    </h2>

                                    <p class="text-xs text-emerald-100">
                                        Upcoming hearing
                                    </p>
                                </div>

                            </div>

                        </div>


                        @php
                            $nextHearing = $blotter->hearings
                                ->where('status', 'Scheduled')
                                ->filter(fn ($hearing) => $hearing->hearing_date->isToday() || $hearing->hearing_date->isFuture())
                                ->sortBy([
                                    ['hearing_date', 'asc'],
                                    ['hearing_time', 'asc'],
                                ])
                                ->first();
                        @endphp


                        @if($nextHearing)

                            <div class="p-5">

                                <div class="text-center">

                                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500">
                                        {{ $nextHearing->hearing_type ?: 'Hearing' }}
                                    </p>

                                    <p class="mt-2 text-3xl font-bold text-gray-900">
                                        {{ $nextHearing->hearing_date->format('M d') }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ $nextHearing->hearing_date->format('Y') }}
                                    </p>

                                    <div class="mt-4 flex items-center justify-center gap-2 text-lg font-semibold text-emerald-600">
                                        <i class="fa-regular fa-clock"></i>

                                        {{ \Carbon\Carbon::parse($nextHearing->hearing_time)->format('g:i A') }}
                                    </div>

                                </div>


                                @if($nextHearing->notes)

                                    <div class="mt-5 rounded-lg bg-gray-50 p-3">

                                        <p class="text-xs font-medium uppercase text-gray-500">
                                            Notes
                                        </p>

                                        <p class="mt-1 text-sm text-gray-700">
                                            {{ $nextHearing->notes }}
                                        </p>

                                    </div>

                                @endif

                            </div>

                        @else

                            <div class="p-6 text-center">

                                <i class="fa-solid fa-calendar-xmark text-3xl text-gray-400"></i>

                                <p class="mt-3 text-sm text-gray-500">
                                    No upcoming hearing scheduled.
                                </p>

                            </div>

                        @endif

                    </div>



                    {{-- Hearing History --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <livewire:blotters.hearing-history
                            :blotterId="$blotter->id"
                        />

                    </div>



                    <!-- {{-- Actions --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                        <h3 class="font-semibold text-gray-900">
                            Actions
                        </h3>

                        <div class="mt-4 space-y-2">

                            <a
                                href="#"
                                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100"
                            >
                                <i class="fa-solid fa-pen w-5 text-center"></i>
                                Edit Blotter
                            </a>

                            <a
                                href="#"
                                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100"
                            >
                                <i class="fa-solid fa-calendar-plus w-5 text-center"></i>
                                Schedule Hearing
                            </a>

                            <button
                                type="button"
                                onclick="window.print()"
                                class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-100"
                            >
                                <i class="fa-solid fa-print w-5 text-center"></i>
                                Print Record
                            </button>

                        </div>

                    </div> -->

                </div>

            </div>

        </div>

    </div>

</x-app-layout>