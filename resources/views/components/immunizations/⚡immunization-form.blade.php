<?php

use App\Models\Resident;
use App\Models\Immunization;
use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component {

    // --- Parent search / link ---
    public string $parentSearch = '';
    public array $residentMatches = [];
    public ?int $resident_id = null;

    // --- Parent info (autofilled if linked, editable if not) ---
    #[Validate('required|string|max:100')]
    public string $parent_first_name = '';

    #[Validate('required|string|max:100')]
    public string $parent_last_name = '';

    public ?string $parent_middle_name = '';

    // --- Address (autofilled if linked, editable if not) ---
    public ?string $block = '';
    public ?string $lot = '';
    public ?string $unit = '';
    public ?string $street = '';
    public ?string $subdivision = '';

    // --- Infant info ---
    #[Validate('required|string|max:100')]
    public string $infant_first_name = '';

    #[Validate('required|string|max:100')]
    public string $infant_last_name = '';

    public ?string $infant_middle_name = '';

    #[Validate('required|date|before_or_equal:today')]
    public ?string $birthday = null;

    #[Validate('required|in:male,female')]
    public string $sex = '';

    public bool $low_birth_weight = false;

    /**
     * Live search residents table as the user types the parent's name.
     * Address now lives on the households table, linked via resident.household_id.
     */
    public function updatedParentSearch(): void
    {
        if (strlen($this->parentSearch) < 2) {
            $this->residentMatches = [];
            return;
        }

        $this->residentMatches = Resident::query()
            ->with('household') // eager load to avoid N+1 when building the preview below
            ->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->parentSearch}%")
                  ->orWhere('last_name', 'like', "%{$this->parentSearch}%");
            })
            ->limit(8)
            ->get()
            ->map(fn ($r) => [
                'id'         => $r->id,
                'first_name' => $r->first_name,
                'last_name'  => $r->last_name,
                'address'    => collect([
                    $r->household?->block,
                    $r->household?->lot,
                    $r->household?->street,
                    $r->household?->subdivision,
                ])->filter()->implode(', '),
            ])
            ->toArray();
    }

    /**
     * Autofill parent + address fields from a selected resident record.
     * Address is pulled through the household relation — guard against
     * a resident who hasn't been assigned a household yet.
     */
    public function selectResident(int $residentId): void
    {
        $resident = Resident::with('household')->findOrFail($residentId);

        $this->resident_id = $resident->id;
        $this->parent_first_name = $resident->first_name;
        $this->parent_last_name = $resident->last_name;
        $this->parent_middle_name = $resident->middle_name;

        $this->block = $resident->household->block ?? null;
        $this->lot = $resident->household->lot ?? null;
        $this->unit = $resident->household->unit ?? null;
        $this->street = $resident->household->street ?? null;
        $this->subdivision = $resident->household->subdivision ?? null;

        if (!$resident->household) {
            $this->addError('parentSearch', 'This resident has no household on file — please fill in the address manually.');
        }

        $this->parentSearch = "{$resident->first_name} {$resident->last_name}";
        $this->residentMatches = [];
    }

    /**
     * Clear the link — user wants to type parent info manually instead.
     */
    public function clearResidentLink(): void
    {
        $this->reset(['resident_id', 'parent_first_name', 'parent_last_name', 'parent_middle_name',
            'block', 'lot', 'unit', 'street', 'subdivision', 'parentSearch']);
    }

    public function save()
    {
        $validated = $this->validate();

        $immunization = Immunization::create([
            'resident_id'        => $this->resident_id, // null if not linked
            'parent_first_name'  => $this->parent_first_name,
            'parent_last_name'   => $this->parent_last_name,
            'parent_middle_name' => $this->parent_middle_name,
            'block'              => $this->block,
            'lot'                => $this->lot,
            'unit'               => $this->unit,
            'street'             => $this->street,
            'subdivision'        => $this->subdivision,
            'infant_first_name'  => $this->infant_first_name,
            'infant_last_name'   => $this->infant_last_name,
            'infant_middle_name' => $this->infant_middle_name,
            'birthday'           => $this->birthday,
            'sex'                => $this->sex,
            'low_birth_weight'   => $this->low_birth_weight,
        ]);

        session()->flash('success', 'Child immunization record created.');

        return redirect()->route('admin.immunizations.show', $immunization);
    }

} ?>

<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">
        <i class="fa-solid fa-syringe text-blue-600 mr-2"></i>
        New Immunization Record
    </h1>

    <form wire:submit="save" class="space-y-8">

        {{-- PARENT / GUARDIAN --}}
        <section>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Parent / Guardian</h2>

            @if (!$resident_id)
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Resident</label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="parentSearch"
                        placeholder="Type parent's name..."
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @if (count($residentMatches) > 0)
                        <ul class="absolute z-10 w-full bg-white border border-gray-200 rounded-md mt-1 shadow-lg max-h-56 overflow-y-auto">
                            @foreach ($residentMatches as $match)
                                <li
                                    wire:click="selectResident({{ $match['id'] }})"
                                    class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm"
                                >
                                    {{ $match['first_name'] }} {{ $match['last_name'] }}
                                    <span class="text-gray-400 text-xs block">
                                        {{ $match['address'] ?: 'No household on file' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">No match? Just fill in the fields below manually.</p>
                </div>
            @else
                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-md px-4 py-2 mb-4">
                    <span class="text-sm text-green-700">
                        <i class="fa-solid fa-check-circle mr-1"></i>
                        Linked to resident record
                    </span>
                    <button type="button" wire:click="clearResidentLink" class="text-xs text-gray-500 hover:text-red-500">
                        Unlink
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" wire:model="parent_first_name" class="w-full rounded-md border-gray-300">
                    @error('parent_first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" wire:model="parent_last_name" class="w-full rounded-md border-gray-300">
                    @error('parent_last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" wire:model="parent_middle_name" class="w-full rounded-md border-gray-300">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Block</label>
                    <input type="text" wire:model="block" class="w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lot</label>
                    <input type="text" wire:model="lot" class="w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" wire:model="unit" class="w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                    <input type="text" wire:model="street" class="w-full rounded-md border-gray-300">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subdivision</label>
                    <input type="text" wire:model="subdivision" class="w-full rounded-md border-gray-300">
                </div>
            </div>
        </section>

        {{-- INFANT --}}
        <section>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Infant</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" wire:model="infant_first_name" class="w-full rounded-md border-gray-300">
                    @error('infant_first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" wire:model="infant_last_name" class="w-full rounded-md border-gray-300">
                    @error('infant_last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" wire:model="infant_middle_name" class="w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Birthday</label>
                    <input type="date" wire:model="birthday" class="w-full rounded-md border-gray-300">
                    @error('birthday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                    <select wire:model="sex" class="w-full rounded-md border-gray-300">
                        <option value="">Select...</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    @error('sex') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <label class="inline-flex items-center mt-4">
                <input type="checkbox" wire:model="low_birth_weight" class="rounded border-gray-300 text-blue-600">
                <span class="ml-2 text-sm text-gray-700">Low birth weight (enables 1/2/3-month follow-up tracking)</span>
            </label>
        </section>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.immunizations.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                <i class="fa-solid fa-save mr-1"></i> Save Record
            </button>
        </div>
    </form>
</div>