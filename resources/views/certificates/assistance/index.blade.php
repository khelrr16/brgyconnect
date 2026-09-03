<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Barangay Services
                        </p>

                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                            My Assistance Requests
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Track the status of your assistance requests and
                            see when they are ready for pickup.
                        </p>

                    </div>


                    <a
                        href="{{ route('certificate.assistance.create') }}"
                        class="inline-flex items-center justify-center gap-2
                               rounded-lg bg-indigo-600 px-4 py-2.5
                               text-sm font-semibold text-white shadow-sm
                               transition hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Request Assistance
                    </a>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CONTENT --}}
        {{-- ================================================= --}}

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">


            {{-- ================================================= --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ================================================= --}}

            @if(session('success'))

                <div
                    class="mb-6 flex items-start gap-3 rounded-xl
                           border border-emerald-200 bg-emerald-50 p-4"
                >

                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-600"></i>

                    <div>

                        <p class="text-sm font-semibold text-emerald-900">
                            Success
                        </p>

                        <p class="mt-1 text-sm text-emerald-700">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- ERROR MESSAGE --}}
            {{-- ================================================= --}}

            @if(session('error'))

                <div
                    class="mb-6 flex items-start gap-3 rounded-xl
                           border border-red-200 bg-red-50 p-4"
                >

                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-600"></i>

                    <div>

                        <p class="text-sm font-semibold text-red-900">
                            Error
                        </p>

                        <p class="mt-1 text-sm text-red-700">
                            {{ session('error') }}
                        </p>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- REQUESTS --}}
            {{-- ================================================= --}}

            @if($requests->count())

                <div class="space-y-5">

                    @foreach($requests as $request)

                        @php

                            $isPending = $request->status === 'pending';
                            $isProcessing = $request->status === 'processing';
                            $isApproved = $request->status === 'approved';
                            $isRejected = $request->status === 'rejected';

                        @endphp


                        {{-- ================================================= --}}
                        {{-- REQUEST CARD --}}
                        {{-- ================================================= --}}

                        <div
                            class="overflow-hidden rounded-2xl border
                                   border-gray-200 bg-white shadow-sm"
                        >

                            {{-- ============================================= --}}
                            {{-- REQUEST HEADER --}}
                            {{-- ============================================= --}}

                            <div class="border-b border-gray-100 px-5 py-5 sm:px-6">

                                <div class="flex flex-col gap-4 sm:flex-row
                                            sm:items-start sm:justify-between">

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-11 w-11 shrink-0
                                                   items-center justify-center
                                                   rounded-xl bg-indigo-50
                                                   text-indigo-600"
                                        >
                                            <i class="fa-solid fa-hand-holding-heart"></i>
                                        </div>

                                        <div>

                                            <p class="text-xs font-medium uppercase
                                                      tracking-wide text-gray-400">
                                                Request ID
                                            </p>

                                            <h2 class="mt-0.5 text-base font-bold text-gray-900">
                                                {{ $request->request_id }}
                                            </h2>

                                            <p class="mt-1 text-xs text-gray-500">
                                                Submitted
                                                {{ $request->created_at->format('F d, Y \a\t h:i A') }}
                                            </p>

                                        </div>

                                    </div>


                                    {{-- Status Badge --}}
                                    @if($isPending)

                                        <span
                                            class="inline-flex items-center gap-2
                                                   self-start rounded-full
                                                   border border-amber-200
                                                   bg-amber-50 px-3 py-1.5
                                                   text-xs font-semibold
                                                   text-amber-700"
                                        >
                                            <i class="fa-solid fa-clock"></i>
                                            Pending Review
                                        </span>

                                    @elseif($isProcessing)

                                        <span
                                            class="inline-flex items-center gap-2
                                                   self-start rounded-full
                                                   border border-blue-200
                                                   bg-blue-50 px-3 py-1.5
                                                   text-xs font-semibold
                                                   text-blue-700"
                                        >
                                            <i class="fa-solid fa-spinner"></i>
                                            Processing
                                        </span>

                                    @elseif($isApproved)

                                        <span
                                            class="inline-flex items-center gap-2
                                                   self-start rounded-full
                                                   border border-emerald-200
                                                   bg-emerald-50 px-3 py-1.5
                                                   text-xs font-semibold
                                                   text-emerald-700"
                                        >
                                            <i class="fa-solid fa-circle-check"></i>
                                            Ready for Pickup
                                        </span>

                                    @elseif($isRejected)

                                        <span
                                            class="inline-flex items-center gap-2
                                                   self-start rounded-full
                                                   border border-red-200
                                                   bg-red-50 px-3 py-1.5
                                                   text-xs font-semibold
                                                   text-red-700"
                                        >
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            Rejected
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- ============================================= --}}
                            {{-- REQUEST DETAILS --}}
                            {{-- ============================================= --}}

                            <div class="px-5 py-5 sm:px-6">

                                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">


                                    {{-- Assistance Type --}}
                                    <div>

                                        <p class="text-xs font-medium text-gray-400">
                                            Assistance Type
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ $request->assistance_type }}
                                        </p>

                                    </div>


                                    {{-- Income Source --}}
                                    <div>

                                        <p class="text-xs font-medium text-gray-400">
                                            Source of Income
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ $request->income_source }}
                                        </p>

                                    </div>


                                    {{-- Addressed To --}}
                                    <div>

                                        <p class="text-xs font-medium text-gray-400">
                                            Addressed To
                                        </p>

                                        <div class="mt-1 flex flex-wrap gap-1.5">

                                            @foreach($request->addressed_to as $agency)

                                                <span
                                                    class="rounded-full bg-purple-50
                                                           px-2.5 py-1 text-[11px]
                                                           font-semibold text-purple-700"
                                                >
                                                    {{ $agency }}
                                                </span>

                                            @endforeach

                                        </div>

                                    </div>

                                </div>


                                {{-- Occupation / Business --}}
                                @if($request->income_source === 'Occupation')

                                    <div
                                        class="mt-5 rounded-xl border border-gray-100
                                               bg-gray-50 p-4"
                                    >

                                        <div class="grid gap-4 sm:grid-cols-2">

                                            <div>

                                                <p class="text-xs text-gray-400">
                                                    Occupation
                                                </p>

                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    {{ $request->occupation ?: '—' }}
                                                </p>

                                            </div>

                                            <div>

                                                <p class="text-xs text-gray-400">
                                                    Monthly Income
                                                </p>

                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    ₱{{ number_format((float) $request->monthly_income, 2) }}
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                @elseif($request->income_source === 'Business')

                                    <div
                                        class="mt-5 rounded-xl border border-gray-100
                                               bg-gray-50 p-4"
                                    >

                                        <div class="grid gap-4 sm:grid-cols-2">

                                            <div>

                                                <p class="text-xs text-gray-400">
                                                    Business Type
                                                </p>

                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    {{ $request->business_type ?: '—' }}
                                                </p>

                                            </div>

                                            <div>

                                                <p class="text-xs text-gray-400">
                                                    Business Duration
                                                </p>

                                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                                    {{ $request->business_duration ?? '—' }}

                                                    @if($request->business_duration !== null)
                                                        {{ Str::plural('year', $request->business_duration) }}
                                                    @endif
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <div
                                        class="mt-5 rounded-xl border border-gray-100
                                               bg-gray-50 px-4 py-3"
                                    >

                                        <p class="text-sm text-gray-600">
                                            <i class="fa-solid fa-circle-info mr-1 text-gray-400"></i>
                                            No source of income declared.
                                        </p>

                                    </div>

                                @endif


                                {{-- Remarks --}}
                                @if($request->remarks)

                                    <div class="mt-5">

                                        <p class="text-xs font-medium text-gray-400">
                                            Remarks
                                        </p>

                                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-600">
                                            {{ $request->remarks }}
                                        </p>

                                    </div>

                                @endif

                            </div>


                            {{-- ============================================= --}}
                            {{-- PROGRESS --}}
                            {{-- ============================================= --}}

                            @if(!$isRejected)

                                <div
                                    class="border-t border-gray-100
                                           bg-gray-50 px-5 py-5 sm:px-6"
                                >

                                    <p class="mb-4 text-xs font-semibold uppercase
                                              tracking-wider text-gray-500">
                                        Request Progress
                                    </p>


                                    <div class="relative">

                                        {{-- Progress Line --}}
                                        <div
                                            class="absolute left-5 right-5 top-5
                                                   h-0.5 bg-gray-200"
                                        ></div>


                                        @php

                                            $progressWidth = match($request->status) {
                                                'pending' => '0%',
                                                'processing' => '50%',
                                                'approved' => '100%',
                                                default => '0%',
                                            };

                                        @endphp

                                        <div
                                            class="absolute left-5 top-5 h-0.5
                                                   bg-indigo-600 transition-all"
                                            style="width: calc({{ $progressWidth }} - 20px);"
                                        ></div>


                                        <div
                                            class="relative grid grid-cols-3"
                                        >

                                            {{-- Pending --}}
                                            <div class="flex flex-col items-start">

                                                <div
                                                    class="
                                                        flex h-10 w-10 items-center
                                                        justify-center rounded-full
                                                        border-2

                                                        @if(in_array($request->status, ['pending', 'processing', 'approved']))
                                                            border-indigo-600 bg-indigo-600 text-white
                                                        @else
                                                            border-gray-300 bg-white text-gray-400
                                                        @endif
                                                    "
                                                >
                                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                                </div>

                                                <p
                                                    class="mt-2 text-xs font-semibold
                                                           @if(in_array($request->status, ['pending', 'processing', 'approved']))
                                                               text-indigo-700
                                                           @else
                                                               text-gray-400
                                                           @endif"
                                                >
                                                    Submitted
                                                </p>

                                                <p class="mt-0.5 text-[11px] text-gray-400">
                                                    Request received
                                                </p>

                                            </div>


                                            {{-- Processing --}}
                                            <div class="flex flex-col items-center">

                                                <div
                                                    class="
                                                        flex h-10 w-10 items-center
                                                        justify-center rounded-full
                                                        border-2

                                                        @if(in_array($request->status, ['processing', 'approved']))
                                                            border-blue-600 bg-blue-600 text-white
                                                        @else
                                                            border-gray-300 bg-white text-gray-400
                                                        @endif
                                                    "
                                                >
                                                    <i class="fa-solid fa-hourglass-half text-xs"></i>
                                                </div>

                                                <p
                                                    class="mt-2 text-xs font-semibold
                                                           @if(in_array($request->status, ['processing', 'approved']))
                                                               text-blue-700
                                                           @else
                                                               text-gray-400
                                                           @endif"
                                                >
                                                    Processing
                                                </p>

                                                <p class="mt-0.5 text-center text-[11px] text-gray-400">
                                                    Being reviewed
                                                </p>

                                            </div>


                                            {{-- Ready --}}
                                            <div class="flex flex-col items-end">

                                                <div
                                                    class="
                                                        flex h-10 w-10 items-center
                                                        justify-center rounded-full
                                                        border-2

                                                        @if($request->status === 'approved')
                                                            border-emerald-600 bg-emerald-600 text-white
                                                        @else
                                                            border-gray-300 bg-white text-gray-400
                                                        @endif
                                                    "
                                                >
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </div>

                                                <p
                                                    class="mt-2 text-xs font-semibold
                                                           @if($request->status === 'approved')
                                                               text-emerald-700
                                                           @else
                                                               text-gray-400
                                                           @endif"
                                                >
                                                    Ready
                                                </p>

                                                <p class="mt-0.5 text-[11px] text-gray-400">
                                                    Ready for pickup
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- ============================================= --}}
                            {{-- APPROVED / READY NOTICE --}}
                            {{-- ============================================= --}}

                            @if($isApproved)

                                <div
                                    class="border-t border-emerald-200
                                           bg-emerald-50 px-5 py-5 sm:px-6"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0
                                                   items-center justify-center
                                                   rounded-full bg-emerald-100
                                                   text-emerald-600"
                                        >
                                            <i class="fa-solid fa-bell"></i>
                                        </div>

                                        <div>

                                            <h3 class="text-sm font-bold text-emerald-900">
                                                Your request is ready
                                            </h3>

                                            <p class="mt-1 text-sm leading-6 text-emerald-700">
                                                Your assistance request has been approved.
                                                Please visit the Barangay Hall to
                                                claim your certificate or document.
                                            </p>

                                            @if($request->processed_at)

                                                <p class="mt-2 text-xs text-emerald-600">
                                                    Approved on
                                                    {{ $request->processed_at->format('F d, Y \a\t h:i A') }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- ============================================= --}}
                            {{-- REJECTED NOTICE --}}
                            {{-- ============================================= --}}

                            @if($isRejected)

                                <div
                                    class="border-t border-red-200
                                           bg-red-50 px-5 py-5 sm:px-6"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0
                                                   items-center justify-center
                                                   rounded-full bg-red-100
                                                   text-red-600"
                                        >
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </div>

                                        <div class="min-w-0">

                                            <h3 class="text-sm font-bold text-red-900">
                                                Request Rejected
                                            </h3>

                                            <p class="mt-1 text-sm leading-6 text-red-700">
                                                Your request was not approved.
                                            </p>

                                            @if($request->rejection_reason)

                                                <div
                                                    class="mt-3 rounded-lg border
                                                           border-red-200 bg-white
                                                           p-4"
                                                >

                                                    <p class="text-xs font-semibold uppercase
                                                              tracking-wide text-red-600">
                                                        Reason
                                                    </p>

                                                    <p
                                                        class="mt-1 whitespace-pre-line
                                                               text-sm leading-6 text-gray-700"
                                                    >
                                                        {{ $request->rejection_reason }}
                                                    </p>

                                                </div>

                                            @endif


                                            <div class="mt-4">

                                                <a
                                                    href="{{ route('certificate.assistance.create') }}"
                                                    class="inline-flex items-center gap-2
                                                           rounded-lg border border-red-200
                                                           bg-white px-4 py-2.5 text-sm
                                                           font-semibold text-red-700
                                                           transition hover:bg-red-50"
                                                >
                                                    <i class="fa-solid fa-rotate-right"></i>
                                                    Submit New Request
                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- ============================================= --}}
                            {{-- PROCESSING NOTICE --}}
                            {{-- ============================================= --}}

                            @if($isProcessing)

                                <div
                                    class="border-t border-blue-200
                                           bg-blue-50 px-5 py-4 sm:px-6"
                                >

                                    <div class="flex items-start gap-3">

                                        <i
                                            class="fa-solid fa-spinner mt-0.5
                                                   text-blue-600"
                                        ></i>

                                        <p class="text-sm leading-6 text-blue-700">
                                            Your request is currently being reviewed
                                            by the barangay office. You will be
                                            notified once it is approved or rejected.
                                        </p>

                                    </div>

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION --}}
                {{-- ================================================= --}}

                <div class="mt-6">
                    {{ $requests->links() }}
                </div>

            @else

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-2xl border border-dashed
                           border-gray-300 bg-white px-6 py-16
                           text-center shadow-sm"
                >

                    <div
                        class="mx-auto flex h-16 w-16 items-center
                               justify-center rounded-full bg-indigo-50
                               text-indigo-500"
                    >
                        <i class="fa-solid fa-hand-holding-heart text-2xl"></i>
                    </div>


                    <h2 class="mt-5 text-lg font-bold text-gray-900">
                        No assistance requests yet
                    </h2>


                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        You haven't submitted an assistance request.
                        Start a request whenever you need barangay assistance.
                    </p>


                    <a
                        href="{{ route('certificate.assistance.create') }}"
                        class="mt-6 inline-flex items-center gap-2
                               rounded-lg bg-indigo-600 px-4 py-2.5
                               text-sm font-semibold text-white shadow-sm
                               transition hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Request Assistance
                    </a>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- BACK TO HOME --}}
            {{-- ================================================= --}}

            <div class="mt-8 text-center">

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-sm
                           font-medium text-gray-500 transition
                           hover:text-indigo-600"
                >
                    <i class="fa-solid fa-house"></i>
                    Back to Home
                </a>

            </div>

        </div>

    </div>

</x-app-layout>