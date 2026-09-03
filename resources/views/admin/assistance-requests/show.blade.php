<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

                <a
                    href="{{ route('admin.assistance-requests.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium
                           text-gray-500 hover:text-indigo-600"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Assistance Requests
                </a>


                <div class="mt-5 flex flex-col gap-4 sm:flex-row
                            sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Assistance Request
                        </p>

                        <h1 class="mt-1 text-2xl font-bold text-gray-900">
                            {{ $assistanceRequest->request_id }}
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Submitted
                            {{ $assistanceRequest->created_at->format('F d, Y h:i A') }}
                        </p>

                    </div>


                    @php

                        $statusClasses = match($assistanceRequest->status) {

                            'pending' =>
                                'bg-amber-50 text-amber-700',

                            'processing' =>
                                'bg-blue-50 text-blue-700',

                            'approved' =>
                                'bg-emerald-50 text-emerald-700',

                            'rejected' =>
                                'bg-red-50 text-red-700',

                            default =>
                                'bg-gray-100 text-gray-700',
                        };

                    @endphp

                    <span
                        class="inline-flex items-center gap-2 self-start
                               rounded-full px-3 py-1.5 text-xs
                               font-semibold {{ $statusClasses }}"
                    >

                        <i class="fa-solid fa-circle"></i>

                        {{ ucfirst($assistanceRequest->status) }}

                    </span>

               
                        <a
                            href="{{ route('admin.certificates.indigency.print', $assistanceRequest) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 rounded-lg
                                bg-indigo-600 px-4 py-2.5 text-sm
                                font-semibold text-white hover:bg-indigo-700"
                        >
                            <i class="fa-solid fa-print"></i>
                            Print Certificate
                        </a>
                    

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CONTENT --}}
        {{-- ================================================= --}}

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="grid gap-6 lg:grid-cols-3">


                {{-- ================================================= --}}
                {{-- REQUEST --}}
                {{-- ================================================= --}}

                <div class="space-y-6 lg:col-span-2">


                    {{-- Resident --}}
                    <div
                        class="rounded-xl border border-gray-200
                               bg-white shadow-sm"
                    >

                        <div class="border-b border-gray-200 px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-blue-50 text-blue-600"
                                >
                                    <i class="fa-solid fa-user"></i>
                                </div>

                                <div>

                                    <h2 class="font-bold text-gray-900">
                                        Resident Information
                                    </h2>

                                    <p class="text-sm text-gray-500">
                                        Official resident record.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="grid gap-5 px-6 py-6 sm:grid-cols-2">

                            <div>
                                <p class="text-xs text-gray-400">
                                    Resident ID
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $assistanceRequest->resident->resident_id }}
                                </p>
                            </div>


                            <div>
                                <p class="text-xs text-gray-400">
                                    Full Name
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $assistanceRequest->resident->full_name }}
                                </p>
                            </div>


                            <div>
                                <p class="text-xs text-gray-400">
                                    Date of Birth
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $assistanceRequest->resident->birth_date?->format('F d, Y') ?? '—' }}
                                </p>
                            </div>


                            <div>
                                <p class="text-xs text-gray-400">
                                    Civil Status
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $assistanceRequest->resident->civil_status ?? '—' }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Assistance --}}
                    <div
                        class="rounded-xl border border-gray-200
                               bg-white shadow-sm"
                    >

                        <div class="border-b border-gray-200 px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-indigo-50 text-indigo-600"
                                >
                                    <i class="fa-solid fa-hand-holding-heart"></i>
                                </div>

                                <div>

                                    <h2 class="font-bold text-gray-900">
                                        Assistance Information
                                    </h2>

                                    <p class="text-sm text-gray-500">
                                        Details submitted by the resident.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="grid gap-5 px-6 py-6 sm:grid-cols-2">


                            <div class="sm:col-span-2">

                                <p class="text-xs text-gray-400">
                                    Assistance Type
                                </p>

                                <p class="mt-1 text-sm font-bold text-gray-900">
                                    {{ $assistanceRequest->assistance_type }}
                                </p>

                            </div>


                            <div class="sm:col-span-2">

                                <p class="text-xs text-gray-400">
                                    Addressed To
                                </p>

                                <div class="mt-2 flex flex-wrap gap-2">

                                    @foreach($assistanceRequest->addressed_to as $agency)

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-purple-50
                                                   px-3 py-1 text-xs font-semibold
                                                   text-purple-700"
                                        >
                                            <i class="fa-solid fa-building"></i>
                                            {{ $agency }}
                                        </span>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Source of Income --}}
                    <div
                        class="rounded-xl border border-gray-200
                               bg-white shadow-sm"
                    >

                        <div class="border-b border-gray-200 px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-emerald-50 text-emerald-600"
                                >
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>

                                <div>

                                    <h2 class="font-bold text-gray-900">
                                        Source of Income
                                    </h2>

                                </div>

                            </div>

                        </div>


                        <div class="grid gap-5 px-6 py-6 sm:grid-cols-2">

                            <div>

                                <p class="text-xs text-gray-400">
                                    Source
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $assistanceRequest->income_source }}
                                </p>

                            </div>


                            @if($assistanceRequest->income_source === 'Occupation')

                                <div>

                                    <p class="text-xs text-gray-400">
                                        Occupation
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $assistanceRequest->occupation }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Monthly Income
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        ₱{{ number_format((float) $assistanceRequest->monthly_income, 2) }}
                                    </p>

                                </div>

                            @elseif($assistanceRequest->income_source === 'Business')

                                <div>

                                    <p class="text-xs text-gray-400">
                                        Business Type
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $assistanceRequest->business_type }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Duration
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $assistanceRequest->business_duration }}
                                        {{ Str::plural('year', $assistanceRequest->business_duration) }}
                                    </p>

                                </div>

                            @else

                                <div class="sm:col-span-2">

                                    <p class="text-sm text-gray-600">
                                        Applicant declared no source of income.
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Remarks --}}
                    @if($assistanceRequest->remarks)

                        <div
                            class="rounded-xl border border-gray-200
                                   bg-white shadow-sm"
                        >

                            <div class="border-b border-gray-200 px-6 py-5">

                                <h2 class="font-bold text-gray-900">
                                    Remarks / Explanation
                                </h2>

                            </div>

                            <div class="px-6 py-6">

                                <p class="whitespace-pre-line text-sm
                                          leading-6 text-gray-600">
                                    {{ $assistanceRequest->remarks }}
                                </p>

                            </div>

                        </div>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- PROCESSING PANEL --}}
                {{-- ================================================= --}}

                <div>

                    <div
                        class="sticky top-6 rounded-xl border border-gray-200
                               bg-white shadow-sm"
                    >

                        <div class="border-b border-gray-200 px-5 py-5">

                            <h2 class="font-bold text-gray-900">
                                Process Request
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Review and update the request status.
                            </p>

                        </div>


                        <div class="space-y-4 px-5 py-5">


                            {{-- Processing --}}
                            @if($assistanceRequest->status === 'pending')

                                <form
                                    method="POST"
                                    action="{{ route('admin.assistance-requests.process', $assistanceRequest) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="inline-flex w-full items-center
                                               justify-center gap-2 rounded-lg
                                               bg-blue-600 px-4 py-2.5
                                               text-sm font-semibold text-white
                                               hover:bg-blue-700"
                                    >
                                        <i class="fa-solid fa-spinner"></i>
                                        Start Processing
                                    </button>

                                </form>

                            @endif


                            {{-- Approve --}}
                            @if(in_array($assistanceRequest->status, ['pending', 'processing']))

                                <form
                                    method="POST"
                                    action="{{ route('admin.assistance-requests.approve', $assistanceRequest) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <label class="mb-1 block text-sm font-medium text-gray-700">
                                        Processing Notes
                                    </label>

                                    <textarea
                                        name="processing_notes"
                                        rows="4"
                                        placeholder="Optional notes about the approval..."
                                        class="w-full rounded-lg border-gray-300
                                               text-sm shadow-sm
                                               focus:border-emerald-500
                                               focus:ring-emerald-500"
                                    ></textarea>

                                    <button
                                        type="submit"
                                        class="mt-3 inline-flex w-full items-center
                                               justify-center gap-2 rounded-lg
                                               bg-emerald-600 px-4 py-2.5
                                               text-sm font-semibold text-white
                                               hover:bg-emerald-700"
                                    >
                                        <i class="fa-solid fa-circle-check"></i>
                                        Approve Request
                                    </button>

                                </form>


                                {{-- Reject --}}
                                <div class="border-t border-gray-200 pt-4">

                                    <form
                                        method="POST"
                                        action="{{ route('admin.assistance-requests.reject', $assistanceRequest) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <label class="mb-1 block text-sm font-medium text-gray-700">
                                            Rejection Reason
                                        </label>

                                        <textarea
                                            name="rejection_reason"
                                            rows="4"
                                            required
                                            placeholder="Explain why the request is being rejected..."
                                            class="w-full rounded-lg border-gray-300
                                                   text-sm shadow-sm
                                                   focus:border-red-500
                                                   focus:ring-red-500"
                                        ></textarea>

                                        <button
                                            type="submit"
                                            class="mt-3 inline-flex w-full items-center
                                                   justify-center gap-2 rounded-lg
                                                   bg-red-600 px-4 py-2.5
                                                   text-sm font-semibold text-white
                                                   hover:bg-red-700"
                                        >
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            Reject Request
                                        </button>

                                    </form>

                                </div>

                            @endif


                            {{-- Finalized --}}
                            @if($assistanceRequest->status === 'approved')

                                <div
                                    class="rounded-lg border border-emerald-200
                                           bg-emerald-50 p-4"
                                >

                                    <div class="flex items-start gap-3">

                                        <i
                                            class="fa-solid fa-circle-check mt-0.5
                                                   text-emerald-600"
                                        ></i>

                                        <div>

                                            <p class="text-sm font-semibold text-emerald-900">
                                                Request Approved
                                            </p>

                                            <p class="mt-1 text-xs leading-5 text-emerald-700">
                                                Processed by
                                                {{ $assistanceRequest->processor?->name ?? '—' }}
                                                on
                                                {{ $assistanceRequest->processed_at?->format('M d, Y h:i A') ?? '—' }}.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @elseif($assistanceRequest->status === 'rejected')

                                <div
                                    class="rounded-lg border border-red-200
                                           bg-red-50 p-4"
                                >

                                    <div class="flex items-start gap-3">

                                        <i
                                            class="fa-solid fa-circle-xmark mt-0.5
                                                   text-red-600"
                                        ></i>

                                        <div>

                                            <p class="text-sm font-semibold text-red-900">
                                                Request Rejected
                                            </p>

                                            <p class="mt-2 text-xs leading-5 text-red-700">
                                                {{ $assistanceRequest->rejection_reason }}
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>