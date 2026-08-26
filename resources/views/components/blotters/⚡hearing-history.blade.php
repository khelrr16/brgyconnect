<?php

use App\Models\BlotterHearing;
use Livewire\Component;

new class extends Component
{
    public int $blotterId;

    public function mount(int $blotterId): void
    {
        $this->blotterId = $blotterId;
    }

    public function updateStatus(
        int $hearingId,
        string $status
    ): void {
        $allowedStatuses = [
            'Scheduled',
            'Completed',
            'Cancelled',
            'Postponed',
        ];

        if (! in_array($status, $allowedStatuses, true)) {
            return;
        }

        $hearing = BlotterHearing::where('id', $hearingId)
            ->where('blotter_record_id', $this->blotterId)
            ->firstOrFail();

        $hearing->update([
            'status' => $status,
        ]);

        session()->flash(
            'success',
            'Hearing status updated successfully.'
        );
    }

    public function render()
    {
        return $this->view([
            'hearings' => BlotterHearing::where(
                'blotter_record_id',
                $this->blotterId
            )
            ->orderByDesc('hearing_date')
            ->orderByDesc('hearing_time')
            ->get(),
        ]);
    }
};
?>

<div>

    <div class="mb-5 flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>

        <div>
            <h2 class="font-semibold text-gray-900">
                Hearing History
            </h2>

            <p class="text-sm text-gray-500">
                Previous hearings
            </p>
        </div>

    </div>

    <div class="relative ml-2 border-l-2 border-gray-200 pl-6">
        @forelse($hearings as $hearing)

            @php
                $statusClass = match($hearing->status) {
                    'Scheduled' => 'bg-blue-100 text-blue-700',
                    'Completed' => 'bg-green-100 text-green-700',
                    'Cancelled' => 'bg-red-100 text-red-700',
                    'Postponed' => 'bg-yellow-100 text-yellow-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp


            <div
                wire:key="hearing-{{ $hearing->id }}"
                class="relative mb-5 border-b border-gray-200 pb-5 last:border-0">

                <div class="absolute -left-[30px] top-1 h-3 w-3 rounded-full bg-purple-500 ring-4 ring-white">
                </div>
                <div class="flex flex-wrap items-start justify-between gap-3">

                    <div>

                        <p class="text-sm font-semibold text-gray-900">
                            {{ $hearing->hearing_date->format('F j, Y') }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($hearing->hearing_time)->format('g:i A') }}
                        </p>

                        <p class="mt-2 text-sm font-medium text-gray-800">
                            {{ $hearing->hearing_type ?: 'Hearing' }}
                        </p>

                    </div>

                    <select
                        wire:change="updateStatus(
                            {{ $hearing->id }},
                            $event.target.value
                        )"
                        class="rounded-full border-0 px-3 py-1.5 pe-6 text-xs font-medium {{ $statusClass }}"
                    >
                        <option
                            value="Scheduled"
                            @selected($hearing->status === 'Scheduled')
                        >
                            Scheduled
                        </option>

                        <option
                            value="Completed"
                            @selected($hearing->status === 'Completed')
                        >
                            Completed
                        </option>

                        <option
                            value="Cancelled"
                            @selected($hearing->status === 'Cancelled')
                        >
                            Cancelled
                        </option>

                        <option
                            value="Postponed"
                            @selected($hearing->status === 'Postponed')
                        >
                            Postponed
                        </option>
                    </select>

                </div>


                @if($hearing->notes)

                    <div class="mt-3 rounded-lg bg-gray-50 p-3">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Notes
                        </p>

                        <p class="mt-1 whitespace-pre-line text-sm text-gray-700">
                            {{ $hearing->notes }}
                        </p>

                    </div>

                @else

                    <p class="mt-3 text-xs italic text-gray-400">
                        No notes recorded.
                    </p>

                @endif
            </div>

        @empty

            <p class="text-sm text-gray-500">
                No hearing records yet.
            </p>

        @endforelse
    </div>

</div>