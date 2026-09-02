<?php

use App\Models\BlotterHearing;
use Livewire\Component;

new class extends Component
{
    public int $blotterId;

    public string $hearing_date = '';
    public string $hearing_time = '';
    public string $hearing_type = '';
    public string $notes = '';

    public function mount(int $blotterId): void
    {
        $this->blotterId = $blotterId;
        $this->hearing_date = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'hearing_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'hearing_time' => [
                'required',
                'date_format:H:i',
            ],

            'hearing_type' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        BlotterHearing::create([
            'blotter_record_id' => $this->blotterId,
            'hearing_date' => $validated['hearing_date'],
            'hearing_time' => $validated['hearing_time'],
            'hearing_type' => $validated['hearing_type'],
            'status' => 'Scheduled',
            'notes' => $validated['notes'],
        ]);

        $this->reset([
            'hearing_time',
            'hearing_type',
            'notes',
        ]);

        $this->hearing_date = now()->format('Y-m-d');

        session()->flash(
            'success',
            'Hearing scheduled successfully.'
        );

        // Tell the browser to refresh the parent Blade page.
        $this->dispatch('hearing-scheduled');
    }

    public function updateStatus(int $hearingId, string $status): void 
    {
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

        $this->dispatch('hearing-updated');
    }

    public function updateNotes(int $hearingId, string $notes): void 
    {
        $hearing = BlotterHearing::where('id', $hearingId)
            ->where('blotter_record_id', $this->blotterId)
            ->firstOrFail();

        $hearing->update([
            'notes' => $notes,
        ]);

        session()->flash(
            'success',
            'Hearing notes updated successfully.'
        );

        $this->dispatch('hearing-updated');
    }

    public function render()
    {
        return $this->view([
            'hearings' => BlotterHearing::where(
                'blotter_record_id',
                $this->blotterId
            )
            ->orderBy('hearing_date')
            ->orderBy('hearing_time')
            ->get(),
        ]);
    }
};
?>

<div
    x-data="{ open: false }"
    x-on:hearing-scheduled.window="open = false"
>
    <button
        type="button"
        @click="open = true"
        class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-1.5 text-xs font-medium text-white hover:bg-white/20"
    >
        <i class="fa-solid fa-calendar-plus"></i>
        Schedule Hearing
    </button>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 px-4 text-gray-900"
    >
        <div
            @click.outside="open = false"
            class="w-full max-w-lg rounded-2xl bg-white shadow-xl"
        >

            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold">
                        Schedule Hearing
                    </h2>

                    <p class="text-sm text-gray-500">
                        Set a hearing for this blotter record.
                    </p>
                </div>

                <button
                    type="button"
                    @click="open = false"
                    class="text-gray-400 hover:text-gray-700"
                >
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            {{-- IMPORTANT --}}
            <form
                wire:submit.prevent="save"
                class="space-y-5 p-6"
            >

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block font-medium">
                            Hearing Date
                        </label>

                        <input
                            type="date"
                            wire:model="hearing_date"
                            class="w-full rounded-lg border-gray-300"
                        >

                        @error('hearing_date')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block font-medium">
                            Hearing Time
                        </label>

                        <input
                            type="time"
                            wire:model="hearing_time"
                            class="w-full rounded-lg border-gray-300"
                        >

                        @error('hearing_time')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1 block font-medium">
                        Hearing Type
                    </label>

                    <select
                        wire:model="hearing_type"
                        class="w-full rounded-lg border-gray-300"
                    >
                        <option value="">Select hearing type</option>
                        <option value="Initial Hearing">Initial Hearing</option>
                        <option value="Mediation">Mediation</option>
                        <option value="Follow-up">Follow-up</option>
                        <option value="Settlement">Settlement</option>
                        <option value="Other">Other</option>
                    </select>

                    @error('hearing_type')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block font-medium">
                        Notes
                    </label>

                    <textarea
                        wire:model="notes"
                        rows="4"
                        class="w-full rounded-lg border-gray-300"
                    ></textarea>
                </div>

                <div class="flex justify-end gap-3 border-t pt-5">

                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-lg bg-gray-100 px-4 py-2 text-sm"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="save">
                            <i class="fa-solid fa-calendar-check mr-1"></i>
                            Schedule
                        </span>

                        <span wire:loading wire:target="save">
                            Scheduling...
                        </span>
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>