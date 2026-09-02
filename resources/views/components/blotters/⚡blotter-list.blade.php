<?php

use App\Models\BlotterRecord;
use App\Models\BlotterHearing;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $baseQuery = BlotterRecord::query();

        $total = (clone $baseQuery)->count();

        $pending = (clone $baseQuery)
            ->where('status', 'Pending')
            ->count();

        $investigation = (clone $baseQuery)
            ->where('status', 'Under Investigation')
            ->count();

        $completed = (clone $baseQuery)
            ->whereIn('status', ['Settled', 'Closed'])
            ->count();

        $upcomingHearings = BlotterHearing::query()
            ->with('blotterRecord')
            ->where('status', 'Scheduled')
            ->where(function ($query) {
                $query
                    ->whereDate('hearing_date', '>=', today());
            })
            ->orderBy('hearing_date')
            ->orderBy('hearing_time')
            ->limit(5)
            ->get();

        $blotters = BlotterRecord::query()
            ->with([
                'hearings' => fn ($query) => $query
                    ->where('status', 'Scheduled')
                    ->whereDate('hearing_date', '>=', today())
                    ->orderBy('hearing_date')
                    ->orderBy('hearing_time'),
                'parties',
            ])
            ->when($this->search, function ($query) {
                $terms = preg_split(
                    '/\s+/',
                    trim($this->search)
                );

                foreach ($terms as $term) {
                    $query->where(function ($query) use ($term) {
                        $query
                            ->where('blotter_number', 'like', "%{$term}%")
                            ->orWhere('incident_type', 'like', "%{$term}%")
                            ->orWhere('incident_location', 'like', "%{$term}%")
                            ->orWhereHas('parties', function ($query) use ($term) {
                                $query
                                    ->where('first_name', 'like', "%{$term}%")
                                    ->orWhere('middle_name', 'like', "%{$term}%")
                                    ->orWhere('last_name', 'like', "%{$term}%");
                            });
                    });
                }
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        $statusCounts = [
            'Pending' => (clone $baseQuery)
                ->where('status', 'Pending')
                ->count(),

            'Under Investigation' => (clone $baseQuery)
                ->where('status', 'Under Investigation')
                ->count(),

            'Settled' => (clone $baseQuery)
                ->where('status', 'Settled')
                ->count(),

            'Closed' => (clone $baseQuery)
                ->where('status', 'Closed')
                ->count(),
        ];

        return $this->view([
            'total' => $total,
            'pending' => $pending,
            'investigation' => $investigation,
            'completed' => $completed,
            'upcomingHearings' => $upcomingHearings,
            'blotters' => $blotters,
            'statusCounts' => $statusCounts,
        ]);
    }
};
?>

<div class="space-y-6">
    
    <div class="grid grid-cols-1 item-stretch gap-4 md:grid-cols-2">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- Total --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Total Records
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $total }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i class="fa-solid fa-file-lines text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Pending --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Pending
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $pending }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                        <i class="fa-solid fa-hourglass-half text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Investigation --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Under Investigation
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $investigation }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </div>

                </div>
            </div>

            {{-- Completed --}}
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Completed
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $completed }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            {{-- Header --}}
            <div class="mb-5 flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Upcoming Hearings
                    </h2>

                    <p class="text-sm text-gray-500">
                        Scheduled hearings for active cases.
                    </p>
                </div>

                <i class="fa-solid fa-calendar-days text-emerald-600"></i>

            </div>


            {{-- Scrollable hearing list --}}
            <div class="max-h-[153px] overflow-y-auto pr-2">

                @forelse($upcomingHearings as $hearing)

                    <a
                        href="{{ route('blotters.show', $hearing->blotterRecord) }}"
                        class="flex flex-row items-center justify-between gap-3 border-b border-gray-100 py-4 last:border-0 hover:bg-gray-50"
                    >

                        <div class="flex items-center gap-4">

                            <div class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">

                                <span class="text-[10px] font-semibold uppercase">
                                    {{ $hearing->hearing_date->format('M') }}
                                </span>

                                <span class="text-lg font-bold leading-none">
                                    {{ $hearing->hearing_date->format('d') }}
                                </span>

                            </div>

                            <div>

                                <p class="font-semibold text-gray-900">
                                    {{ $hearing->hearing_type ?: 'Hearing' }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $hearing->blotterRecord->blotter_number }}
                                    ·
                                    {{ \Carbon\Carbon::parse($hearing->hearing_time)->format('g:i A') }}
                                </p>

                            </div>

                        </div>

                        <i class="fa-solid fa-chevron-right text-gray-300"></i>

                    </a>

                @empty

                    <div class="py-10 text-center">

                        <i class="fa-regular fa-calendar-xmark text-3xl text-gray-300"></i>

                        <p class="mt-3 text-sm text-gray-500">
                            No upcoming hearings.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>
    </div>

    {{-- Search / Filter --}}
    <div class="rounded-2xl bg-white p-6 shadow-md ring-1 ring-gray-200">
        <div class="flex flex-col gap-3 lg:flex-row">

            <div class="relative flex-1">

                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search blotter number, incident, resident, complainant..."
                    class="w-full rounded-lg border-gray-300 pl-10"
                >

            </div>

            <select
                wire:model.live="status"
                class="rounded-lg border-gray-300 lg:w-56"
            >
                <option value="">All Statuses</option>
                <option value="Pending">Pending</option>
                <option value="Under Investigation">Under Investigation</option>
                <option value="Settled">Settled</option>
                <option value="Referred">Referred</option>
                <option value="Closed">Closed</option>
            </select>

        </div>

    </div>


    {{-- Blotter Cards --}}
    <div class="space-y-4">

        @forelse($blotters as $blotter)

            @php
                $statusClass = match($blotter->status) {
                    'Pending' => 'bg-yellow-100 text-yellow-800',
                    'Under Investigation' => 'bg-blue-100 text-blue-800',
                    'Settled' => 'bg-green-100 text-green-800',
                    'Referred' => 'bg-purple-100 text-purple-800',
                    'Closed' => 'bg-gray-100 text-gray-800',
                    default => 'bg-gray-100 text-gray-800',
                };

                $statusIcon = match($blotter->status) {
                    'Pending' => 'fa-hourglass-half',
                    'Under Investigation' => 'fa-magnifying-glass',
                    'Settled' => 'fa-handshake',
                    'Referred' => 'fa-share-from-square',
                    'Closed' => 'fa-circle-check',
                    default => 'fa-circle',
                };

                $incidentIcon = match(strtolower($blotter->incident_type)) {
                    'theft' => 'fa-money-bill-transfer',
                    'physical injury' => 'fa-user-injured',
                    'threat' => 'fa-triangle-exclamation',
                    'harassment' => 'fa-person-circle-exclamation',
                    'property dispute' => 'fa-house',
                    'noise complaint' => 'fa-volume-high',
                    'domestic dispute' => 'fa-people-arrows',
                    default => 'fa-file-circle-exclamation',
                };

                $complainant = $blotter->parties
                    ->firstWhere('role', 'Complainant');

                $nextHearing = $blotter->hearings->first();
            @endphp


            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 transition hover:shadow-lg">

                {{-- Card Header --}}
                <div class="flex flex-col gap-4 border-b border-gray-100 p-5 sm:flex-row sm:items-start sm:justify-between">

                    <div class="flex items-start gap-4">

                        {{-- Incident Icon --}}
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <i class="fa-solid {{ $incidentIcon }} text-lg"></i>
                        </div>

                        <div>

                            <div class="flex flex-wrap items-center gap-2">

                                <a
                                    href="{{ route('blotters.show', $blotter) }}"
                                    class="text-lg font-bold text-gray-900 hover:text-blue-600"
                                >
                                    {{ $blotter->blotter_number }}
                                </a>

                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    <i class="fa-solid {{ $statusIcon }} text-[10px]"></i>
                                    {{ $blotter->status }}
                                </span>

                            </div>

                            <p class="mt-1 font-medium text-gray-700">
                                {{ $blotter->incident_type }}
                            </p>

                            <p class="mt-1 flex items-center gap-1.5 text-sm text-gray-500">
                                <i class="fa-solid fa-location-dot text-gray-400"></i>
                                {{ $blotter->incident_location }}
                            </p>

                        </div>

                    </div>


                    {{-- View Button --}}
                    <a
                        href="{{ route('blotters.show', $blotter) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        <i class="fa-solid fa-eye"></i>
                        View
                    </a>

                </div>


                {{-- Card Body --}}
                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-3">

                    {{-- Incident Date --}}
                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Incident Date
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $blotter->incident_date->format('F j, Y') }}
                            </p>
                        </div>

                    </div>


                    {{-- Complainant --}}
                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>

                        <div class="min-w-0">

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Complainant
                            </p>

                            <p class="mt-1 truncate text-sm font-semibold text-gray-800">
                                @if($complainant)
                                    {{ $complainant->last_name }},
                                    {{ $complainant->first_name }}
                                @else
                                    N/A
                                @endif
                            </p>

                        </div>

                    </div>


                    {{-- People --}}
                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                            <i class="fa-solid fa-users"></i>
                        </div>

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                People Involved
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $blotter->parties->count() }} person(s)
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Next Hearing --}}
                <div class="border-t border-gray-100 bg-gray-50 px-5 py-4">

                    @if($nextHearing)

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>

                                <div>

                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Next Hearing
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800">

                                        {{ $nextHearing->hearing_date->format('F j, Y') }}

                                        ·

                                        {{ \Carbon\Carbon::parse($nextHearing->hearing_time)->format('g:i A') }}

                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $nextHearing->hearing_type ?: 'Hearing' }}
                                    </p>

                                </div>

                            </div>

                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                <i class="fa-solid fa-clock"></i>
                                Scheduled
                            </span>

                        </div>

                    @else

                        <div class="flex items-center gap-3 text-gray-400">

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                <i class="fa-regular fa-calendar-xmark"></i>
                            </div>

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide">
                                    Next Hearing
                                </p>

                                <p class="mt-1 text-sm">
                                    No hearing scheduled.
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        @empty

            <div class="rounded-2xl bg-white px-6 py-14 text-center shadow-sm ring-1 ring-gray-200">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <i class="fa-regular fa-folder-open text-2xl"></i>
                </div>

                <p class="mt-4 font-semibold text-gray-700">
                    No blotter records found
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Try changing your search or status filter.
                </p>

            </div>

        @endforelse


        {{-- Pagination --}}
        @if($blotters->hasPages())

            <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-gray-200">
                {{ $blotters->links() }}
            </div>

        @endif

    </div>

</div>