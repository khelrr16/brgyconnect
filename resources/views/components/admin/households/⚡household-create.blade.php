<?php

use App\Models\Household;
use Livewire\Component;

new class extends Component
{
    public string $subdivision = '';
    public string $block = '';
    public string $lot = '';
    public string $unit = '';
    public string $street = '';

    public ?Household $existingHousehold = null;

    public bool $checkedAddress = false;

    public function checkAddress(): void
    {
        $this->validate([
            'subdivision' => ['nullable', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:100'],
            'lot' => ['nullable', 'string', 'max:100'],
            'unit' => ['nullable', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
        ]);

        $this->existingHousehold = Household::query()
            ->whereRaw('LOWER(TRIM(COALESCE(subdivision, ""))) = ?', [
                strtolower(trim($this->subdivision))
            ])
            ->whereRaw('LOWER(TRIM(COALESCE(block, ""))) = ?', [
                strtolower(trim($this->block))
            ])
            ->whereRaw('LOWER(TRIM(COALESCE(lot, ""))) = ?', [
                strtolower(trim($this->lot))
            ])
            ->whereRaw('LOWER(TRIM(COALESCE(unit, ""))) = ?', [
                strtolower(trim($this->unit))
            ])
            ->whereRaw('LOWER(TRIM(street)) = ?', [
                strtolower(trim($this->street))
            ])
            ->withCount('residents')
            ->first();

        $this->checkedAddress = true;
    }

    public function createHousehold()
    {
        $this->validate([
            'subdivision' => ['nullable', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:100'],
            'lot' => ['nullable', 'string', 'max:100'],
            'unit' => ['nullable', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check again before inserting
        |--------------------------------------------------------------------------
        |
        | This protects against duplicate household creation even if
        | another admin created the household after the first check.
        |
        */

        $existing = Household::query()
            ->whereRaw('LOWER(TRIM(COALESCE(subdivision, ""))) = ?', [
                strtolower(trim($this->subdivision))
            ])
            ->whereRaw('LOWER(TRIM(COALESCE(block, ""))) = ?', [
                strtolower(trim($this->block))
            ])
            ->whereRaw('LOWER(TRIM(COALESCE(lot, ""))) = ?', [
                strtolower(trim($this->lot))
            ])
            ->whereRaw('LOWER(TRIM(COALESCE(unit, ""))) = ?', [
                strtolower(trim($this->unit))
            ])
            ->whereRaw('LOWER(TRIM(street)) = ?', [
                strtolower(trim($this->street))
            ])
            ->first();

        if ($existing) {
            $this->existingHousehold = $existing;
            $this->checkedAddress = true;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Household ID
        |--------------------------------------------------------------------------
        */
        $household = Household::create([

            'subdivision' => trim($this->subdivision) ?: null,
            'block' => trim($this->block) ?: null,
            'lot' => trim($this->lot) ?: null,
            'unit' => trim($this->unit) ?: null,
            'street' => trim($this->street),
        ]);

        return redirect()
            ->route('admin.households.show', $household)
            ->with('success', 'Household created successfully.');
    }
};
?>

<div class="min-h-screen bg-gray-50">

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="border-b border-gray-200 bg-white">

        <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

            <a
                href="{{ route('admin.households.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium
                        text-gray-500 hover:text-indigo-600"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to Households
            </a>


            <div class="mt-5">

                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                    Household Management
                </p>

                <h1 class="mt-1 text-2xl font-bold text-gray-900">
                    Add Household
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Enter the household address to check whether
                    a household already exists.
                </p>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- FORM --}}
    {{-- ================================================= --}}

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5 sm:px-8">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 items-center justify-center
                                rounded-lg bg-indigo-50 text-indigo-600"
                    >
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div>

                        <h2 class="font-bold text-gray-900">
                            Household Address
                        </h2>

                        <p class="text-sm text-gray-500">
                            Start with the subdivision and continue down to
                            the street.
                        </p>

                    </div>

                </div>

            </div>


            <div class="px-6 py-6 sm:px-8">

                <div class="space-y-5">


                    {{-- ================================================= --}}
                    {{-- SUBDIVISION --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700">
                            Subdivision
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <select
                        wire:model="subdivision"
                        class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                        >

                            <option value="">
                                --Select--
                            </option>

                            <option value="Conpil I Village">
                                Conpil I Village
                            </option>

                            <option value="Conpil III Executive">
                                Conpil III Executive
                            </option>

                            <option value="Console 1 Village">
                                Console 1 Village
                            </option>

                            <option value="Greatland Village">
                                Greatland Village
                            </option>

                            <option value="Guevara Subdivision">
                                Guevara Subdivision
                            </option>

                            <option value="Pacita 2A">
                                Pacita 2A
                            </option>

                            <option value="Pacita 2B">
                                Pacita 2B
                            </option>
                        </select>

                        @error('subdivision')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- BLOCK --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700">
                            Block
                            <span class="font-normal text-gray-400">
                                (Number only)
                            </span>
                        </label>

                        <input
                            type="number"
                            wire:model.live.debounce.300ms="block"
                            placeholder="e.g. 5"
                            class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                        >

                        @error('block')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- LOT --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700">
                            Lot
                            <span class="font-normal text-gray-400">
                                (Number only)
                            </span>
                        </label>

                        <input
                            type="text"
                            wire:model.live.debounce.300ms="lot"
                            placeholder="e.g. 13 and 14"
                            class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                        >

                        @error('lot')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- UNIT --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700">
                            Unit
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="text"
                            wire:model.live.debounce.300ms="unit"
                            class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                        >

                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- STREET --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700">
                            Street
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            wire:model.live.debounce.300ms="street"
                            placeholder="e.g. Mabini Street"
                            class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                        >

                        @error('street')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- CHECK BUTTON --}}
                    {{-- ================================================= --}}

                    <div class="pt-2">

                        <button
                            type="button"
                            wire:click="checkAddress"
                            wire:loading.attr="disabled"
                            class="inline-flex w-full items-center
                                    justify-center gap-2 rounded-lg
                                    bg-indigo-600 px-4 py-3 text-sm
                                    font-semibold text-white shadow-sm
                                    transition hover:bg-indigo-700
                                    disabled:cursor-not-allowed
                                    disabled:opacity-60"
                        >

                            <span wire:loading.remove wire:target="checkAddress">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Check Address
                            </span>

                            <span wire:loading wire:target="checkAddress">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                Checking...
                            </span>

                        </button>

                    </div>


                    {{-- ================================================= --}}
                    {{-- EXISTING HOUSEHOLD --}}
                    {{-- ================================================= --}}

                    @if($checkedAddress && $existingHousehold)

                        <div
                            class="rounded-xl border border-amber-200
                                    bg-amber-50 p-5"
                        >

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0
                                            items-center justify-center
                                            rounded-full bg-amber-100
                                            text-amber-600"
                                >
                                    <i class="fa-solid fa-house-circle-exclamation"></i>
                                </div>

                                <div class="min-w-0">

                                    <p
                                        class="text-xs font-semibold
                                                uppercase tracking-wider
                                                text-amber-700"
                                    >
                                        Existing Household Found
                                    </p>

                                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                                        {{ $existingHousehold->household_id }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-600">
                                        A household already exists at this address.
                                    </p>


                                    {{-- Address --}}
                                    <div
                                        class="mt-4 rounded-lg border
                                                border-amber-200 bg-white
                                                px-4 py-3"
                                    >

                                        <p class="text-xs font-medium text-gray-400">
                                            Address
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-900">

                                            @if($existingHousehold->unit)
                                                {{ $existingHousehold->unit }},
                                            @endif

                                            @if($existingHousehold->lot)
                                                Lot {{ $existingHousehold->lot }},
                                            @endif

                                            @if($existingHousehold->block)
                                                Block {{ $existingHousehold->block }},
                                            @endif

                                            @if($existingHousehold->street)
                                                {{ $existingHousehold->street }},
                                            @endif

                                            @if($existingHousehold->subdivision)
                                                {{ $existingHousehold->subdivision }}
                                            @endif

                                        </p>

                                    </div>


                                    <div class="mt-4 flex flex-wrap gap-3">

                                        <a
                                            href="{{ route('admin.households.show', $existingHousehold) }}"
                                            class="inline-flex items-center gap-2
                                                    rounded-lg bg-amber-600
                                                    px-4 py-2.5 text-sm
                                                    font-semibold text-white
                                                    hover:bg-amber-700"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                            View Household

                                        </a>


                                        <button
                                            type="button"
                                            wire:click="$set('checkedAddress', false)"
                                            class="inline-flex items-center gap-2
                                                    rounded-lg border border-gray-200
                                                    bg-white px-4 py-2.5 text-sm
                                                    font-semibold text-gray-700
                                                    hover:bg-gray-50"
                                        >
                                            <i class="fa-solid fa-pen"></i>
                                            Change Address
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @elseif($checkedAddress)

                        {{-- ================================================= --}}
                        {{-- NO EXISTING HOUSEHOLD --}}
                        {{-- ================================================= --}}

                        <div
                            class="rounded-xl border border-emerald-200
                                    bg-emerald-50 p-5"
                        >

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0
                                            items-center justify-center
                                            rounded-full bg-emerald-100
                                            text-emerald-600"
                                >
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>

                                <div>

                                    <p
                                        class="text-xs font-semibold uppercase
                                                tracking-wider text-emerald-700"
                                    >
                                        Address Available
                                    </p>

                                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                                        No household found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-600">
                                        No existing household was found at
                                        this address. You can create a new
                                        household record.
                                    </p>


                                    <button
                                        type="button"
                                        wire:click="createHousehold"
                                        wire:loading.attr="disabled"
                                        class="mt-4 inline-flex items-center
                                                gap-2 rounded-lg bg-emerald-600
                                                px-4 py-2.5 text-sm font-semibold
                                                text-white hover:bg-emerald-700
                                                disabled:cursor-not-allowed
                                                disabled:opacity-60"
                                    >

                                        <span
                                            wire:loading.remove
                                            wire:target="createHousehold"
                                        >
                                            <i class="fa-solid fa-plus"></i>
                                            Create Household
                                        </span>

                                        <span
                                            wire:loading
                                            wire:target="createHousehold"
                                        >
                                            <i class="fa-solid fa-spinner fa-spin"></i>
                                            Creating...
                                        </span>

                                    </button>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>