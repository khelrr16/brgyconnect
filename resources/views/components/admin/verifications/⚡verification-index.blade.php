<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AccountVerification;

new class extends Component {
    use WithPagination;

    public string $statusFilter = 'pending';

    public function with(): array
    {
        return [
            'requests' => AccountVerification::with('user')
                ->when($this->statusFilter !== 'all', fn ($q) =>
                    $q->where('status', $this->statusFilter))
                ->latest()
                ->paginate(15),
        ];
    }
} ?>

<div>

    {{-- ================================================= --}}
    {{-- TOOLBAR --}}
    {{-- ================================================= --}}

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        {{-- Page description --}}
        <div>
            <h3 class="text-lg font-bold text-gray-900">
                Account Verifications
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Review and verify registered user accounts.
            </p>
        </div>


        {{-- Status Filter --}}
        <div class="flex items-center gap-3">

            <label
                for="statusFilter"
                class="text-sm font-medium text-gray-600">
                Filter:
            </label>

            <div class="relative">
                <select
                    id="statusFilter"
                    wire:model.live="statusFilter"
                    class="w-40 appearance-none
                           rounded-lg
                           border border-gray-300
                           bg-white
                           py-2 pl-9 pr-8
                           text-sm text-gray-700
                           shadow-sm
                           transition
                           hover:border-gray-400
                           focus:border-indigo-500
                           focus:ring-2
                           focus:ring-indigo-500/20"
                >
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="all">All Requests</option>
                </select>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- TABLE CARD --}}
    {{-- ================================================= --}}

    <div
        class="overflow-hidden
               rounded-xl
               border border-gray-200
               bg-white
               shadow-sm"
    >

        {{-- Table Header --}}
        <div
            class="flex items-center justify-between
                   border-b border-gray-100
                   bg-gray-50/70
                   px-6 py-4">

            <div class="flex items-center gap-2">

                <div
                    class="flex h-9 w-9 items-center justify-center
                           rounded-lg
                           bg-indigo-100
                           text-indigo-600"
                >
                    <i class="fa-solid fa-user-check"></i>
                </div>

                <div>

                    <h4 class="text-sm font-semibold text-gray-900">
                        Verification Requests
                    </h4>

                    <p class="text-xs text-gray-500">
                        Accounts awaiting review
                    </p>

                </div>

            </div>


            {{-- Request Count --}}
            <div
                class="rounded-full
                       bg-white
                       border border-gray-200
                       px-3 py-1
                       text-xs font-medium
                       text-gray-600"
            >
                {{ $requests->total() }}
                {{ Str::plural('request', $requests->total()) }}
            </div>

        </div>


        {{-- ================================================= --}}
        {{-- RESPONSIVE TABLE --}}
        {{-- ================================================= --}}

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th
                            class="px-6 py-3.5
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wider
                                   text-gray-500"
                        >
                            Applicant
                        </th>

                        <th
                            class="px-6 py-3.5
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wider
                                   text-gray-500"
                        >
                            Address
                        </th>

                        <th
                            class="px-6 py-3.5
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wider
                                   text-gray-500"
                        >
                            Status
                        </th>

                        <th
                            class="px-6 py-3.5
                                   text-right
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wider
                                   text-gray-500"
                        >
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse ($requests as $req)

                        <tr
                            wire:key="verification-{{ $req->id }}"
                            class="transition-colors duration-150
                                   hover:bg-gray-50/70"
                        >

                            {{-- ================================================= --}}
                            {{-- APPLICANT --}}
                            {{-- ================================================= --}}

                            <td class="px-6 py-4 whitespace-nowrap">

                                <div class="flex items-center gap-3">

                                    {{-- Avatar --}}
                                    <div
                                        class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-full
                                               bg-indigo-100
                                               text-sm
                                               font-bold
                                               text-indigo-700">

                                        {{ strtoupper(substr($req->first_name, 0, 1)) }}
                                    </div>


                                    <div>

                                        <div
                                            class="text-sm
                                                   font-semibold
                                                   text-gray-900">
                                                   
                                            {{ $req->first_name }}

                                            @if($req->middle_name)
                                                {{ $req->middle_name }}
                                            @endif

                                            {{ $req->last_name }}

                                            @if($req->extension_name)
                                                {{ $req->extension_name }}
                                            @endif
                                        </div>

                                        @if($req->user)
                                            <div
                                                class="mt-0.5
                                                       flex items-center gap-1.5
                                                       text-xs text-gray-500"
                                            >
                                                <i class="fa-solid fa-envelope text-gray-400"></i>

                                                {{ $req->user->email }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- ADDRESS --}}
                            {{-- ================================================= --}}

                            <td class="px-6 py-4">

                                <div
                                    class="flex items-start gap-2
                                           max-w-xs"
                                >

                                    <i
                                        class="fa-solid fa-location-dot
                                               mt-0.5
                                               text-gray-400"
                                    ></i>

                                    <div
                                        class="text-sm
                                               leading-5
                                               text-gray-600"
                                    >

                                        @if($req->unit)
                                            <span>
                                                Unit {{ $req->unit }},
                                            </span>
                                        @endif

                                        @if($req->block)
                                            <span>
                                                Block {{ $req->block }},
                                            </span>
                                        @endif

                                        @if($req->lot)
                                            <span>
                                                Lot {{ $req->lot }},
                                            </span>
                                        @endif

                                        @if($req->street)
                                            <span>
                                                {{ $req->street }},
                                            </span>
                                        @endif

                                        @if($req->subdivision)
                                            <span>
                                                {{ $req->subdivision }}
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- STATUS --}}
                            {{-- ================================================= --}}

                            <td class="px-6 py-4 whitespace-nowrap">

                                @if($req->status === 'pending')

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full
                                               bg-amber-50
                                               px-3 py-1.5
                                               text-xs
                                               font-semibold
                                               text-amber-700
                                               ring-1
                                               ring-inset
                                               ring-amber-600/20"
                                    >
                                        <i class="fa-solid fa-clock"></i>
                                        Pending
                                    </span>

                                @elseif($req->status === 'approved')

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full
                                               bg-emerald-50
                                               px-3 py-1.5
                                               text-xs
                                               font-semibold
                                               text-emerald-700
                                               ring-1
                                               ring-inset
                                               ring-emerald-600/20"
                                    >
                                        <i class="fa-solid fa-circle-check"></i>
                                        Approved
                                    </span>

                                @elseif($req->status === 'rejected')

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full
                                               bg-red-50
                                               px-3 py-1.5
                                               text-xs
                                               font-semibold
                                               text-red-700
                                               ring-1
                                               ring-inset
                                               ring-red-600/20"
                                    >
                                        <i class="fa-solid fa-circle-xmark"></i>
                                        Rejected
                                    </span>

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- ACTION --}}
                            {{-- ================================================= --}}

                            <td class="px-6 py-4 text-right whitespace-nowrap">

                                <a
                                    href="{{ route(
                                        'admin.verifications.show',
                                        $req
                                    ) }}"
                                    class="inline-flex
                                           items-center
                                           gap-2
                                           rounded-lg
                                           border
                                           border-indigo-200
                                           bg-indigo-50
                                           px-3.5
                                           py-2
                                           text-sm
                                           font-semibold
                                           text-indigo-700
                                           shadow-sm
                                           transition-all
                                           duration-150
                                           hover:border-indigo-300
                                           hover:bg-indigo-100
                                           hover:shadow
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-indigo-500
                                           focus:ring-offset-2"
                                >

                                    <i class="fa-solid fa-eye"></i>

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        {{-- ================================================= --}}
                        {{-- EMPTY STATE --}}
                        {{-- ================================================= --}}

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-16 text-center"
                            >

                                <div
                                    class="flex flex-col
                                           items-center
                                           justify-center"
                                >

                                    <div
                                        class="mb-4
                                               flex h-14 w-14
                                               items-center
                                               justify-center
                                               rounded-full
                                               bg-gray-100
                                               text-gray-400"
                                    >
                                        <i
                                            class="fa-solid fa-user-clock text-xl"
                                        ></i>
                                    </div>

                                    <h4
                                        class="text-sm
                                               font-semibold
                                               text-gray-700"
                                    >
                                        No verification requests
                                    </h4>

                                    <p
                                        class="mt-1
                                               max-w-sm
                                               text-sm
                                               text-gray-500"
                                    >
                                        There are no accounts matching the
                                        selected verification status.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================================================= --}}
        {{-- PAGINATION --}}
        {{-- ================================================= --}}

        @if($requests->hasPages())

            <div
                class="border-t
                       border-gray-100
                       bg-gray-50/50
                       px-6 py-4"
            >
                {{ $requests->links() }}
            </div>

        @endif

    </div>

</div>