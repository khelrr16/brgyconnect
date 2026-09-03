<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\AccountVerification;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public AccountVerification $verification;
    public string $search = '';
    public ?int $selectedResidentId = null;
    public ?Resident $selectedResident = null;
    public bool $showRejectModal = false;
    public string $rejectionReason = '';

    public function mount(AccountVerification $verification): void
    {
        $this->verification = $verification;
    }

    #[Computed]
    public function matchingResidents()
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return Resident::query()
            ->with('household')
            ->where(fn ($q) => $q
                ->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$this->search}%"])
                ->orWhere('resident_id', 'like', "%{$this->search}%")
                ->orWhereHas('household', fn ($household) => $household
                    ->where('household_id', 'like', "%{$this->search}%")
                    ->orWhere('block', 'like', "%{$this->search}%")
                    ->orWhere('lot', 'like', "%{$this->search}%")
                    ->orWhere('unit', 'like', "%{$this->search}%")
                    ->orWhere('street', 'like', "%{$this->search}%")
                    ->orWhere('subdivision', 'like', "%{$this->search}%"))
            )
            ->whereDoesntHave('user')
            ->limit(10)
            ->get();
    }

    public function selectResident(int $residentId): void
    {
        $this->selectedResident = Resident::find($residentId);
        $this->selectedResidentId = $residentId;
    }

    public function linkAndApprove(): void
    {
        $this->validate([
            'selectedResidentId' => 'required|exists:residents,id',
        ]);

        DB::transaction(function () {
            $user = $this->verification->user()
                ->lockForUpdate()
                ->firstOrFail();

            $resident = Resident::query()->findOrFail($this->selectedResidentId);

            $user->resident_id = $resident->id;
            $user->saveOrFail();

            $user->refresh();

            if ((int) $user->resident_id !== (int) $resident->id) {
                throw new \RuntimeException('The resident could not be linked to the user.');
            }

            $this->verification->update([
                'status' => 'approved',
                'resident_id' => $resident->id,
            ]);
        });

        session()->flash('success', 'Account linked and approved.');
        $this->redirect(route('admin.verifications.index'), navigate: true);
    }

    public function reject(): void
    {
        $this->validate([
            'rejectionReason' => 'required|min:5',
        ]);

        $this->verification->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        session()->flash('success', 'Request rejected.');
        $this->redirect(route('admin.verifications.index'), navigate: true);
    }

    public function noMatchFound(): void
    {
        $this->verification->update([
            'status' => 'rejected',
            'rejection_reason' => 'No matching resident record found. Please fill out the resident registration form: ' . config('app.resident_google_form_url'),
        ]);

        session()->flash('success', 'User notified to complete the Google Form.');
        $this->redirect(route('admin.verifications.index'), navigate: true);
    }
} ?>

<div class="space-y-6">

    {{-- ================================================= --}}
    {{-- SUBMITTED INFORMATION + RESIDENT SEARCH --}}
    {{-- ================================================= --}}

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ================================================= --}}
        {{-- SUBMITTED INFORMATION --}}
        {{-- ================================================= --}}

        <div
            class="overflow-hidden
                   rounded-xl
                   border border-gray-200
                   bg-white
                   shadow-sm"
        >

            {{-- Card Header --}}
            <div
                class="flex items-center gap-3
                       border-b border-gray-100
                       bg-gray-50/70
                       px-5 py-4"
            >

                <div
                    class="flex h-10 w-10 items-center justify-center
                           rounded-lg
                           bg-indigo-100
                           text-indigo-600"
                >
                    <i class="fa-solid fa-user"></i>
                </div>

                <div>

                    <h3 class="text-sm font-bold text-gray-900">
                        Submitted Information
                    </h3>

                    <p class="text-xs text-gray-500">
                        Information provided by the applicant
                    </p>

                </div>

            </div>


            {{-- Information --}}
            <div class="p-5">

                <dl class="divide-y divide-gray-100">

                    {{-- Name --}}
                    <div class="grid grid-cols-3 gap-4 py-3 first:pt-0">

                        <dt
                            class="text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-400"
                        >
                            Full Name
                        </dt>

                        <dd
                            class="col-span-2
                                   text-sm
                                   font-semibold
                                   text-gray-800"
                        >
                            {{ $verification->first_name }}

                            @if($verification->middle_name)
                                {{ $verification->middle_name }}
                            @endif

                            {{ $verification->last_name }}

                            @if($verification->extension_name)
                                {{ $verification->extension_name }}
                            @endif
                        </dd>

                    </div>


                    {{-- Birthday --}}
                    @if($verification->birth_date)

                        <div class="grid grid-cols-3 gap-4 py-3">

                            <dt
                                class="text-xs font-semibold
                                       uppercase tracking-wide
                                       text-gray-400"
                            >
                                Birthday
                            </dt>

                            <dd class="col-span-2 text-sm text-gray-700">

                                <i
                                    class="fa-solid fa-cake-candles
                                           mr-2 text-gray-400"
                                ></i>

                                {{ \Carbon\Carbon::parse($verification->birth_date)->format('F j, Y') }}

                            </dd>

                        </div>

                    @endif


                    {{-- Address --}}
                    <div class="grid grid-cols-3 gap-4 py-3">

                        <dt
                            class="text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-400"
                        >
                            Address
                        </dt>

                        <dd
                            class="col-span-2
                                   text-sm
                                   leading-6
                                   text-gray-700"
                        >

                            <div class="flex items-start gap-2">

                                <i
                                    class="fa-solid fa-location-dot
                                           mt-1
                                           text-gray-400"
                                ></i>

                                <span>

                                    @if($verification->unit)
                                        Unit {{ $verification->unit }},
                                    @endif

                                    @if($verification->block)
                                        Block {{ $verification->block }},
                                    @endif

                                    @if($verification->lot)
                                        Lot {{ $verification->lot }},
                                    @endif

                                    @if($verification->street)
                                        {{ $verification->street }},
                                    @endif

                                    {{ $verification->subdivision }}

                                </span>

                            </div>

                        </dd>

                    </div>

                </dl>


                {{-- Verification Status --}}
                <div class="mt-5 border-t border-gray-100 pt-4">

                    <div
                        class="flex items-center
                               justify-between"
                    >

                        <span
                            class="text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-400"
                        >
                            Verification Status
                        </span>


                        @if($verification->status === 'pending')

                            <span
                                class="inline-flex items-center gap-1.5
                                       rounded-full
                                       bg-amber-50
                                       px-3 py-1.5
                                       text-xs font-semibold
                                       text-amber-700
                                       ring-1 ring-inset
                                       ring-amber-600/20"
                            >
                                <i class="fa-solid fa-clock"></i>
                                Pending Review
                            </span>

                        @elseif($verification->status === 'approved')

                            <span
                                class="inline-flex items-center gap-1.5
                                       rounded-full
                                       bg-emerald-50
                                       px-3 py-1.5
                                       text-xs font-semibold
                                       text-emerald-700
                                       ring-1 ring-inset
                                       ring-emerald-600/20"
                            >
                                <i class="fa-solid fa-circle-check"></i>
                                Approved
                            </span>

                        @elseif($verification->status === 'rejected')

                            <span
                                class="inline-flex items-center gap-1.5
                                       rounded-full
                                       bg-red-50
                                       px-3 py-1.5
                                       text-xs font-semibold
                                       text-red-700
                                       ring-1 ring-inset
                                       ring-red-600/20"
                            >
                                <i class="fa-solid fa-circle-xmark"></i>
                                Rejected
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- MATCH TO RESIDENT --}}
        {{-- ================================================= --}}

        <div
            class="overflow-hidden
                   rounded-xl
                   border border-gray-200
                   bg-white
                   shadow-sm"
        >

            {{-- Card Header --}}
            <div
                class="border-b border-gray-100
                       bg-gray-50/70
                       px-5 py-4"
            >

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-lg
                               bg-blue-100
                               text-blue-600"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <div>

                        <h3 class="text-sm font-bold text-gray-900">
                            Match to Resident
                        </h3>

                        <p class="text-xs text-gray-500">
                            Find the applicant in the resident database
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-5">

                {{-- Search --}}
                <div class="relative">

                    <i
                        class="fa-solid fa-magnifying-glass
                               pointer-events-none
                               absolute left-3 top-1/2
                               -translate-y-1/2
                               text-gray-400"
                    ></i>

                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search name or resident ID..."
                        class="w-full rounded-lg
                               border border-gray-300
                               bg-white
                               py-2.5 pl-10 pr-4
                               text-sm
                               shadow-sm
                               transition
                               placeholder:text-gray-400
                               hover:border-gray-400
                               focus:border-indigo-500
                               focus:ring-2
                               focus:ring-indigo-500/20"
                    >

                </div>


                {{-- Search hint --}}
                <div
                    class="mt-2 flex items-center gap-1.5
                           text-xs text-gray-400"
                >
                    <i class="fa-solid fa-circle-info"></i>

                    <span>
                        Search by resident name or Resident ID.
                    </span>

                </div>


                {{-- Results --}}
                <div class="mt-4">

                    @if(strlen($search) >= 2)

                        <div
                            class="overflow-hidden
                                   rounded-lg
                                   border border-gray-200"
                        >

                            <div
                                class="border-b border-gray-100
                                       bg-gray-50
                                       px-4 py-2"
                            >

                                <span
                                    class="text-xs
                                           font-semibold
                                           uppercase
                                           tracking-wide
                                           text-gray-500"
                                >
                                    Matching Residents
                                </span>

                            </div>


                            <ul
                                class="max-h-64
                                       divide-y divide-gray-100
                                       overflow-y-auto"
                            >

                                @forelse ($this->matchingResidents as $resident)

                                    <li
                                        wire:key="resident-{{ $resident->id }}"
                                        wire:click="selectResident({{ $resident->id }})"
                                        @class([
                                            'group cursor-pointer px-4 py-3 transition-colors',
                                            'bg-indigo-50 ring-1 ring-inset ring-indigo-200'
                                                => $selectedResidentId === $resident->id,
                                            'hover:bg-gray-50'
                                                => $selectedResidentId !== $resident->id,
                                        ])
                                    >

                                        <div class="flex items-center gap-3">

                                            {{-- Avatar --}}
                                            <div
                                                @class([
                                                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold',
                                                    'bg-indigo-200 text-indigo-700'
                                                        => $selectedResidentId === $resident->id,
                                                    'bg-gray-100 text-gray-500'
                                                        => $selectedResidentId !== $resident->id,
                                                ])
                                            >
                                                {{ strtoupper(substr($resident->first_name, 0, 1)) }}
                                            </div>


                                            <div class="min-w-0 flex-1">

                                                <div
                                                    class="flex items-center
                                                           justify-between gap-2"
                                                >

                                                    <p
                                                        class="truncate
                                                               text-sm
                                                               font-semibold
                                                               text-gray-800"
                                                    >
                                                        {{ $resident->full_name }}
                                                    </p>

                                                    @if($selectedResidentId === $resident->id)

                                                        <i
                                                            class="fa-solid fa-circle-check
                                                                   shrink-0
                                                                   text-indigo-600"
                                                        ></i>

                                                    @endif

                                                </div>


                                                <p
                                                    class="mt-0.5
                                                           truncate
                                                           text-xs
                                                           text-gray-500"
                                                >
                                                    <i class="fa-solid fa-id-card mr-1"></i>
                                                    {{ $resident->resident_id }}
                                                </p>


                                                @if($resident->household?->full_address)

                                                    <p
                                                        class="mt-0.5
                                                               truncate
                                                               text-xs
                                                               text-gray-400"
                                                    >
                                                        <i
                                                            class="fa-solid fa-location-dot mr-1"
                                                        ></i>

                                                        {{ $resident->household->full_address }}
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </li>

                                @empty

                                    <li class="px-4 py-8 text-center">

                                        <div
                                            class="mx-auto mb-2
                                                   flex h-10 w-10
                                                   items-center
                                                   justify-center
                                                   rounded-full
                                                   bg-gray-100
                                                   text-gray-400"
                                        >
                                            <i class="fa-solid fa-user-slash"></i>
                                        </div>

                                        <p
                                            class="text-sm
                                                   font-medium
                                                   text-gray-600"
                                        >
                                            No matching residents
                                        </p>

                                        <p
                                            class="mt-1
                                                   text-xs
                                                   text-gray-400"
                                        >
                                            Try a different name or Resident ID.
                                        </p>

                                    </li>

                                @endforelse

                            </ul>

                        </div>

                    @else

                        <div
                            class="rounded-lg
                                   border border-dashed
                                   border-gray-300
                                   bg-gray-50
                                   px-4 py-8
                                   text-center"
                        >

                            <div
                                class="mx-auto mb-3
                                       flex h-11 w-11
                                       items-center
                                       justify-center
                                       rounded-full
                                       bg-white
                                       text-gray-400
                                       shadow-sm"
                            >
                                <i class="fa-solid fa-user-magnifying-glass"></i>
                            </div>

                            <p
                                class="text-sm
                                       font-medium
                                       text-gray-600"
                            >
                                Search for a resident
                            </p>

                            <p
                                class="mt-1
                                       text-xs
                                       text-gray-400"
                            >
                                Enter at least 2 characters to begin searching.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- SELECTED RESIDENT --}}
                {{-- ================================================= --}}

                @if($this->selectedResident)

                    <div
                        class="mt-5
                               rounded-lg
                               border border-indigo-200
                               bg-indigo-50/60
                               p-4"
                    >

                        <div
                            class="mb-3 flex items-center
                                   justify-between"
                        >

                            <div class="flex items-center gap-2">

                                <i
                                    class="fa-solid fa-link
                                           text-indigo-600"
                                ></i>

                                <span
                                    class="text-xs
                                           font-bold
                                           uppercase
                                           tracking-wide
                                           text-indigo-700"
                                >
                                    Selected Resident
                                </span>

                            </div>

                            <span
                                class="text-xs
                                       font-medium
                                       text-indigo-500"
                            >
                                ID:
                                {{ $this->selectedResident->resident_id }}
                            </span>

                        </div>


                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-11 w-11
                                       shrink-0
                                       items-center
                                       justify-center
                                       rounded-full
                                       bg-indigo-100
                                       text-sm
                                       font-bold
                                       text-indigo-700"
                            >
                                {{ strtoupper(substr($this->selectedResident->first_name, 0, 1)) }}
                            </div>


                            <div>

                                <p
                                    class="text-sm
                                           font-bold
                                           text-gray-900"
                                >
                                    {{ $this->selectedResident->full_name }}
                                </p>

                                @if($this->selectedResident->address)

                                    <p
                                        class="mt-0.5
                                               text-xs
                                               text-gray-500"
                                    >
                                        {{ $this->selectedResident->address }}
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- ACTIONS --}}
    {{-- ================================================= --}}

    @if($verification->status === 'pending')

        <div
            class="rounded-xl
                   border border-gray-200
                   bg-white
                   p-5
                   shadow-sm"
        >

            <div
                class="flex flex-col
                       gap-4
                       sm:flex-row
                       sm:items-center
                       sm:justify-between"
            >

                <div>

                    <h3 class="text-sm font-bold text-gray-900">
                        Verification Decision
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Confirm that the submitted information belongs to the
                        selected resident before approving.
                    </p>

                </div>


                <div class="flex flex-wrap items-center gap-2">

                    {{-- Link & Approve --}}
                    <button
                        type="button"
                        wire:click="linkAndApprove"
                        @disabled(! $selectedResidentId)
                        wire:loading.attr="disabled"
                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               rounded-lg
                               border border-indigo-700
                               bg-indigo-600
                               px-4 py-2.5
                               text-sm
                               font-semibold
                               text-white
                               shadow-sm
                               transition-all
                               hover:bg-indigo-700
                               hover:shadow-md
                               disabled:cursor-not-allowed
                               disabled:opacity-40
                               focus:outline-none
                               focus:ring-2
                               focus:ring-indigo-500
                               focus:ring-offset-2"
                    >

                        <i
                            wire:loading.remove
                            wire:target="linkAndApprove"
                            class="fa-solid fa-link"
                        ></i>

                        <i
                            wire:loading
                            wire:target="linkAndApprove"
                            class="fa-solid fa-spinner fa-spin"
                        ></i>

                        <span>
                            Link & Approve
                        </span>

                    </button>


                    {{-- Reject --}}
                    <button
                        type="button"
                        wire:click="$set('showRejectModal', true)"
                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               rounded-lg
                               border border-red-200
                               bg-red-50
                               px-4 py-2.5
                               text-sm
                               font-semibold
                               text-red-700
                               shadow-sm
                               transition-all
                               hover:border-red-300
                               hover:bg-red-100
                               hover:shadow
                               focus:outline-none
                               focus:ring-2
                               focus:ring-red-500
                               focus:ring-offset-2"
                    >

                        <i class="fa-solid fa-xmark"></i>

                        Reject

                    </button>


                    {{-- No Match --}}
                    <button
                        type="button"
                        wire:click="noMatchFound"
                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               rounded-lg
                               border border-gray-300
                               bg-white
                               px-4 py-2.5
                               text-sm
                               font-semibold
                               text-gray-700
                               shadow-sm
                               transition-all
                               hover:bg-gray-50
                               hover:shadow
                               focus:outline-none
                               focus:ring-2
                               focus:ring-gray-400
                               focus:ring-offset-2"
                    >

                        <i class="fa-solid fa-file-arrow-up"></i>

                        No Match

                    </button>

                </div>

            </div>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- REJECT MODAL --}}
    {{-- ================================================= --}}

    @if($showRejectModal)

        <div
            class="fixed inset-0 z-50
                   flex items-center justify-center
                   bg-gray-900/50
                   px-4
                   backdrop-blur-sm"
        >

            <div
                class="w-full max-w-md
                       overflow-hidden
                       rounded-xl
                       bg-white
                       shadow-2xl
                       ring-1 ring-black/10"
            >

                {{-- Modal Header --}}
                <div
                    class="flex items-center
                           justify-between
                           border-b border-gray-100
                           px-5 py-4"
                >

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-9 w-9
                                   items-center justify-center
                                   rounded-lg
                                   bg-red-100
                                   text-red-600"
                        >
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>

                        <div>

                            <h4
                                class="text-sm
                                       font-bold
                                       text-gray-900"
                            >
                                Reject Verification
                            </h4>

                            <p
                                class="mt-0.5
                                       text-xs
                                       text-gray-500"
                            >
                                Provide a reason for the rejection.
                            </p>

                        </div>

                    </div>


                    <button
                        type="button"
                        wire:click="$set('showRejectModal', false)"
                        class="flex h-8 w-8
                               items-center justify-center
                               rounded-lg
                               text-gray-400
                               hover:bg-gray-100
                               hover:text-gray-600"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>


                {{-- Modal Body --}}
                <div class="p-5">

                    <label
                        for="rejectionReason"
                        class="mb-2 block
                               text-sm
                               font-semibold
                               text-gray-700"
                    >
                        Rejection Reason
                    </label>

                    <textarea
                        id="rejectionReason"
                        wire:model="rejectionReason"
                        rows="4"
                        placeholder="Explain why this verification request is being rejected..."
                        class="w-full
                               rounded-lg
                               border border-gray-300
                               px-3 py-2.5
                               text-sm
                               shadow-sm
                               placeholder:text-gray-400
                               focus:border-red-500
                               focus:ring-2
                               focus:ring-red-500/20"
                    ></textarea>


                    @error('rejectionReason')

                        <p
                            class="mt-1.5
                                   flex items-center gap-1.5
                                   text-xs
                                   font-medium
                                   text-red-600"
                        >
                            <i class="fa-solid fa-circle-exclamation"></i>

                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Modal Footer --}}
                <div
                    class="flex items-center
                           justify-end gap-2
                           border-t border-gray-100
                           bg-gray-50/50
                           px-5 py-4"
                >

                    <button
                        type="button"
                        wire:click="$set('showRejectModal', false)"
                        class="rounded-lg
                               border border-gray-300
                               bg-white
                               px-4 py-2
                               text-sm
                               font-medium
                               text-gray-700
                               shadow-sm
                               hover:bg-gray-50"
                    >
                        Cancel
                    </button>


                    <button
                        type="button"
                        wire:click="reject"
                        wire:loading.attr="disabled"
                        class="inline-flex
                               items-center
                               gap-2
                               rounded-lg
                               border border-red-700
                               bg-red-600
                               px-4 py-2
                               text-sm
                               font-semibold
                               text-white
                               shadow-sm
                               hover:bg-red-700
                               disabled:opacity-50"
                    >

                        <i
                            wire:loading.remove
                            wire:target="reject"
                            class="fa-solid fa-xmark"
                        ></i>

                        <i
                            wire:loading
                            wire:target="reject"
                            class="fa-solid fa-spinner fa-spin"
                        ></i>

                        Reject Request

                    </button>

                </div>

            </div>

        </div>

    @endif

</div>