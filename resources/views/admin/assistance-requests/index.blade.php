<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Barangay Services
                        </p>

                        <h1 class="mt-1 text-2xl font-bold text-gray-900">
                            Assistance Requests
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Review and process resident assistance requests.
                        </p>

                    </div>


                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-lg bg-indigo-50 text-indigo-600"
                    >
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CONTENT --}}
        {{-- ================================================= --}}

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">


            {{-- FILTER --}}
            <div
                class="mb-6 flex flex-col gap-4 rounded-xl
                       border border-gray-200 bg-white p-4
                       shadow-sm sm:flex-row sm:items-center
                       sm:justify-between"
            >

                <div>

                    <p class="text-sm font-semibold text-gray-900">
                        Request Queue
                    </p>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Review requests based on their current status.
                    </p>

                </div>


                <form method="GET">

                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="rounded-lg border-gray-300 text-sm
                               shadow-sm focus:border-indigo-500
                               focus:ring-indigo-500"
                    >

                        <option
                            value="pending"
                            @selected($status === 'pending')
                        >
                            Pending
                        </option>

                        <option
                            value="processing"
                            @selected($status === 'processing')
                        >
                            Processing
                        </option>

                        <option
                            value="approved"
                            @selected($status === 'approved')
                        >
                            Approved
                        </option>

                        <option
                            value="rejected"
                            @selected($status === 'rejected')
                        >
                            Rejected
                        </option>

                        <option
                            value="all"
                            @selected($status === 'all')
                        >
                            All Requests
                        </option>

                    </select>

                </form>

            </div>


            {{-- ================================================= --}}
            {{-- TABLE --}}
            {{-- ================================================= --}}

            <div
                class="overflow-hidden rounded-xl border border-gray-200
                       bg-white shadow-sm"
            >

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Request
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Resident
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Assistance
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($requests as $request)

                                <tr class="transition hover:bg-gray-50">

                                    {{-- Request --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $request->request_id }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $request->created_at->format('M d, Y h:i A') }}
                                        </p>

                                    </td>


                                    {{-- Resident --}}
                                    <td class="px-6 py-4">

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $request->resident->full_name ?? 'Resident' }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $request->resident->resident_id }}
                                        </p>

                                    </td>


                                    {{-- Assistance --}}
                                    <td class="px-6 py-4">

                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $request->assistance_type }}
                                        </p>

                                        <div class="mt-1 flex flex-wrap gap-1">

                                            @foreach($request->addressed_to as $agency)

                                                <span
                                                    class="rounded-full bg-purple-50
                                                           px-2 py-0.5 text-[11px]
                                                           font-semibold text-purple-700"
                                                >
                                                    {{ $agency }}
                                                </span>

                                            @endforeach

                                        </div>

                                    </td>


                                    {{-- Status --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        @php

                                            $statusClasses = match($request->status) {

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
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full px-2.5 py-1
                                                   text-xs font-semibold
                                                   {{ $statusClasses }}"
                                        >

                                            @if($request->status === 'pending')
                                                <i class="fa-solid fa-clock"></i>
                                            @elseif($request->status === 'processing')
                                                <i class="fa-solid fa-spinner"></i>
                                            @elseif($request->status === 'approved')
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            @endif

                                            {{ ucfirst($request->status) }}

                                        </span>

                                    </td>


                                    {{-- Action --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-right">

                                        <a
                                            href="{{ route('admin.assistance-requests.show', $request) }}"
                                            class="inline-flex items-center gap-2
                                                   rounded-lg border border-gray-200
                                                   bg-white px-3 py-2 text-xs
                                                   font-semibold text-gray-700
                                                   hover:border-indigo-200
                                                   hover:bg-indigo-50
                                                   hover:text-indigo-600"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                            Review
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="px-6 py-16 text-center"
                                    >

                                        <div
                                            class="mx-auto flex h-14 w-14
                                                   items-center justify-center
                                                   rounded-full bg-gray-100
                                                   text-gray-400"
                                        >
                                            <i class="fa-solid fa-inbox text-xl"></i>
                                        </div>

                                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                                            No requests found
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            There are no assistance requests
                                            matching this status.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if($requests->hasPages())

                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $requests->links() }}
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>