<?php

use App\Models\Household;
use App\Models\Resident;
use Livewire\Component;

new class extends Component
{
    public Resident $resident;

    public string $subdivision = '';
    public string $block = '';
    public string $lot = '';
    public string $unit = '';
    public string $street = '';

    public ?Household $existingHousehold = null;
    public bool $checkedAddress = false;

    public function mount(Resident $resident): void
    {
        $this->resident = $resident;
        $household = $resident->household;

        if ($household) {
            $this->subdivision = $household->subdivision ?? '';
            $this->block = $household->block ?? '';
            $this->lot = $household->lot ?? '';
            $this->unit = $household->unit ?? '';
            $this->street = $household->street ?? '';
        }
    }

    protected function addressRules(): array
    {
        return [
            'subdivision' => ['nullable', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:100'],
            'lot' => ['nullable', 'string', 'max:100'],
            'unit' => ['nullable', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
        ];
    }

    protected function addressQuery()
    {
        return Household::query()
            ->whereRaw('LOWER(TRIM(COALESCE(subdivision, ""))) = ?', [strtolower(trim($this->subdivision))])
            ->whereRaw('LOWER(TRIM(COALESCE(block, ""))) = ?', [strtolower(trim($this->block))])
            ->whereRaw('LOWER(TRIM(COALESCE(lot, ""))) = ?', [strtolower(trim($this->lot))])
            ->whereRaw('LOWER(TRIM(COALESCE(unit, ""))) = ?', [strtolower(trim($this->unit))])
            ->whereRaw('LOWER(TRIM(street)) = ?', [strtolower(trim($this->street))]);
    }

    public function findHousehold(): void
    {
        $this->validate($this->addressRules());
        $this->existingHousehold = $this->addressQuery()->withCount('residents')->first();
        $this->checkedAddress = true;
    }

    public function assignExistingHousehold(): void
    {
        abort_unless($this->existingHousehold, 404);

        $this->resident->update(['household_id' => $this->existingHousehold->id]);

        session()->flash('success', 'Resident moved to the existing household.');
        $this->redirect(route('admin.residents.show', $this->resident), navigate: true);
    }

    public function createHousehold(): void
    {
        $this->validate($this->addressRules());

        $household = $this->addressQuery()->first();

        if (! $household) {
            $household = Household::create([
                'subdivision' => trim($this->subdivision) ?: null,
                'block' => trim($this->block) ?: null,
                'lot' => trim($this->lot) ?: null,
                'unit' => trim($this->unit) ?: null,
                'street' => trim($this->street),
            ]);
        }

        $this->resident->update(['household_id' => $household->id]);

        session()->flash('success', 'Resident moved to the household.');
        $this->redirect(route('admin.residents.show', $this->resident), navigate: true);
    }
};
?>

<div x-data="{ showForm: false }" class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="font-semibold text-gray-900">Change Household</h3>
            <p class="text-sm text-gray-500">Search for another household or create a new one.</p>
        </div>

        <button
            type="button"
            @click="showForm = !showForm"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
        >
            <i class="fa-solid" :class="showForm ? 'fa-chevron-up' : 'fa-house'" aria-hidden="true"></i>
            <span x-text="showForm ? 'Hide Form' : 'Change Household'"></span>
        </button>
    </div>

    <div x-show="showForm" x-cloak class="mt-4">
        <form wire:submit="findHousehold" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
            <div>
                <label for="household-subdivision" class="text-sm text-gray-600">Subdivision</label>
                <input id="household-subdivision" wire:model="subdivision" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                @error('subdivision') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="household-block" class="text-sm text-gray-600">Block</label>
                <input id="household-block" wire:model="block" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                @error('block') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="household-lot" class="text-sm text-gray-600">Lot</label>
                <input id="household-lot" wire:model="lot" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                @error('lot') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="household-unit" class="text-sm text-gray-600">Unit</label>
                <input id="household-unit" wire:model="unit" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                @error('unit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="household-street" class="text-sm text-gray-600">Street</label>
                <input id="household-street" wire:model="street" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                @error('street') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
            <i class="fa-solid fa-magnifying-glass"></i>
            Find Household
        </button>
        </form>

        @if ($checkedAddress)
            @if ($existingHousehold)
                <div class="mt-4 flex flex-col gap-3 rounded-lg border border-green-200 bg-green-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-semibold text-green-900">Existing household found: {{ $existingHousehold->household_id }}</p>
                        <p class="text-sm text-green-800">{{ $existingHousehold->full_address }} ({{ $existingHousehold->residents_count }} members)</p>
                    </div>
                    <button type="button" wire:click="assignExistingHousehold" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        <i class="fa-solid fa-check"></i>
                        Use This Household
                    </button>
                </div>
            @else
                <div class="mt-4 flex flex-col gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-amber-900">No household matches this exact address.</p>
                    <button type="button" wire:click="createHousehold" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        <i class="fa-solid fa-house"></i>
                        Create and Use Household
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>
