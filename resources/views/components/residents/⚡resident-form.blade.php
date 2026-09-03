<?php

use App\Models\Household;
use App\Models\Resident;
use Livewire\Component;

new class extends Component
{
    public ?Resident $resident = null;
    public ?int $household_id = null;

    public int $step = 1;

    // =========================
    // PERSONAL IDENTIFICATION
    // =========================

    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $extension_name = '';

    public string $sex = '';
    public string $birth_date = '';
    public string $civil_status = '';
    public string $citizenship = 'Filipino';
    public string $place_of_birth = '';
    public ?string $contact_number = null;
    public string $registered_voter = '';

    public string $house_ownership = '';
    public string $relationship_to_head = '';
    public string $residence_since = '';

    // =========================
    // SOCIO-ECONOMIC DATA
    // =========================

    public string $educational_attainment = '';
    public string $out_of_school = '';
    public string $employment_status = '';
    public string $religion = '';
    public string $occupation = '';
    public string $is_ofw = '';
    public string $ofw_country = '';

    // =========================
    // SECTORAL / SPECIAL CLASSIFICATION
    // =========================

    public string $is_pwd = '';
    public string $is_indigenous = '';
    public string $indigenous_group = '';
    public string $is_solo_parent = '';

    public function mount(?Household $household = null): void
    {
        $this->household_id = $this->resident?->household_id ?? $household?->id;

        if ($this->resident === null) {
            return;
        }

        $this->first_name = $this->resident->first_name ?? '';
        $this->middle_name = $this->resident->middle_name ?? '';
        $this->last_name = $this->resident->last_name ?? '';
        $this->extension_name = $this->resident->extension_name ?? '';
        $this->sex = $this->resident->sex ?? '';
        $this->birth_date = $this->resident->birth_date?->format('Y-m-d') ?? '';
        $this->civil_status = $this->resident->civil_status ?? '';
        $this->citizenship = $this->resident->citizenship ?? '';
        $this->place_of_birth = $this->resident->place_of_birth ?? '';
        $this->contact_number = $this->resident->contact_number;
        $this->registered_voter = $this->resident->registered_voter ?? '';
        $this->house_ownership = $this->resident->house_ownership ?? '';
        $this->relationship_to_head = $this->resident->relationship_to_head ?? '';
        $this->residence_since = (string) ($this->resident->residence_since ?? '');
        $this->educational_attainment = $this->resident->educational_attainment ?? '';
        $this->out_of_school = $this->resident->out_of_school ?? '';
        $this->employment_status = $this->resident->employment_status ?? '';
        $this->religion = $this->resident->religion ?? '';
        $this->occupation = $this->resident->occupation ?? '';
        $this->is_ofw = $this->resident->is_ofw ?? '';
        $this->ofw_country = $this->resident->ofw_country ?? '';
        $this->is_pwd = $this->resident->is_pwd ?? '';
        $this->is_indigenous = $this->resident->is_indigenous ?? '';
        $this->indigenous_group = $this->resident->indigenous_group ?? '';
        $this->is_solo_parent = $this->resident->is_solo_parent ?? '';
    }

    // =========================
    // VALIDATION
    // =========================

    protected function rules(): array
    {
        return [

            'household_id' => ['required', 'integer', 'exists:households,id'],

            // Personal
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'extension_name' => ['nullable', 'string', 'max:50'],

            'sex' => [
                'required',
                'in:Male,Female',
            ],

            'birth_date' => [
                'required',
                'date'
            ],

            'civil_status' => [
                'required',
                'in:Single,Married,Widow/Widower,Divorced,Legally Separated'
            ],

            'citizenship' => [
                'required',
                'string',
                'max:100'
            ],

            'place_of_birth' => [
                'required',
                'string',
                'max:255'
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:20'
            ],

            'registered_voter' => [
                'required',
                'in:Yes - within,Yes - elsewhere,No'
            ],

            'house_ownership' => [
                'required',
                'string',
                'max:100'
            ],

            'relationship_to_head' => [
                'required',
                'string',
                'max:100'
            ],

            'residence_since' => [
                'required',
                'integer',
                'min:1900',
                'max:' . date('Y')
            ],

            // Socio-economic
            'educational_attainment' => [
                'required',
                'string',
                'max:255'
            ],

            'employment_status' => [
                'required',
                'string',
                'max:100'
            ],

            'religion' => [
                'nullable',
                'string',
                'max:100'
            ],

            'occupation' => [
                'nullable',
                'string',
                'max:255'
            ],

            'out_of_school' => ['required', 'in:Yes,No'],
            'is_ofw' => ['required', 'in:Yes,No'],
            'ofw_country' => ['nullable', 'required_if:is_ofw,Yes', 'string', 'max:100'],
            'is_pwd' => ['required', 'in:Yes,No'],
            'is_indigenous' => ['required', 'in:Yes,No'],
            'indigenous_group' => ['nullable', 'required_if:is_indigenous,Yes', 'string', 'max:100'],
            'is_solo_parent' => ['required', 'in:Yes,No'],
        ];
    }

    public function updated($property): void
    {
        $this->sanitizeField($property);

        if ($property === 'is_ofw' && ! $this->is_ofw) {
            $this->ofw_country = '';
        }

        if ($property === 'is_indigenous' && ! $this->is_indigenous) {
            $this->indigenous_group = '';
        }
    }

    protected function sanitizeField(string $property): void
    {
        $textFields = [
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'citizenship',
            'place_of_birth',
            'religion',
            'occupation',
            'ofw_country',
            'indigenous_group',
        ];

        $numericFields = [
            'contact_number',
            'residence_since',
        ];

        $nullableNumericFields = [
            'contact_number',
        ];

        if (in_array($property, $textFields, true)) {
            $this->{$property} = trim(preg_replace(
                '/[^\pL\pN\s]/u',
                '',
                (string) $this->{$property}
            ));
            $this->{$property} = preg_replace('/\s+/', ' ', $this->{$property});
        }

        if (in_array($property, $numericFields, true)) {
            if (trim((string) $this->{$property}) === '') {
                if (in_array($property, $nullableNumericFields, true)) {
                    $this->{$property} = null;
                }

                return;
            }

            $this->{$property} = preg_replace('/[^0-9]/', '', (string) $this->{$property});
        }
    }

    protected function sanitizeFields(): void
    {
        foreach ([
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'citizenship',
            'place_of_birth',
            'religion',
            'occupation',
            'ofw_country',
            'indigenous_group',
            'contact_number',
            'residence_since',
        ] as $property) {
            $this->sanitizeField($property);
        }
    }

    // =========================
    // NEXT STEP
    // =========================

    public function nextStep()
    {
        $this->validateStep();

        if ($this->step < 2) {
            $this->step++;
            $this->dispatch('resident-form-scroll-top');
        }
    }

    // =========================
    // PREVIOUS STEP
    // =========================

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->dispatch('resident-form-scroll-top');
        }
    }

    // =========================
    // VALIDATE CURRENT STEP
    // =========================

    protected function validateStep()
    {
        $this->sanitizeFields();

        $fields = match ($this->step) {

            1 => [
                'household_id',
                'house_ownership',
                'relationship_to_head',
                'residence_since',
                'first_name',
                'middle_name',
                'last_name',
                'extension_name',
                'sex',
                'birth_date',
                'civil_status',
                'citizenship',
                'place_of_birth',
                'contact_number',
                'registered_voter',
            ],

            2 => [
                'educational_attainment',
                'out_of_school',
                'employment_status',
                'religion',
                'occupation',
                'is_ofw',
                'ofw_country',
                'is_pwd',
                'is_indigenous',
                'indigenous_group',
                'is_solo_parent',
            ],
        };

        $this->validate(
            collect($this->rules())
                ->only($fields)
                ->toArray()
        );
    }

    // =========================
    // SAVE
    // =========================

    public function save()
    {
        $this->sanitizeFields();

        try {

            $validated = $this->validate();

            if ($validated['is_ofw'] === 'No') {
                $validated['ofw_country'] = null;
            }

            if ($validated['is_indigenous'] === 'No') {
                $validated['indigenous_group'] = null;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->step = $this->stepForError(
                array_key_first($e->errors())
            );

            throw $e;
        }

        if ($this->resident) {
            $this->resident->update($validated);
            $message = 'Resident updated successfully.';
        } else {
            Resident::create($validated);
            $message = 'Resident registered successfully.';
        }

        session()->flash(
            'success',
            $message
        );

        return $this->redirectRoute('admin.households.show', $this->household_id);
    }

    // =========================
    // FIND STEP WITH ERROR
    // =========================

    protected function stepForError(string $field): int
    {
        $personalAndResidency = [
            'household_id',
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'sex',
            'birth_date',
            'civil_status',
            'citizenship',
            'place_of_birth',
            'contact_number',
            'registered_voter',
            'house_ownership',
            'relationship_to_head',
            'residence_since',
        ];

        if (in_array($field, $personalAndResidency)) {
            return 1;
        }

        return 2;
    }
};
?>

<div
    class="py-8"
    x-data
    x-on:resident-form-scroll-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
>

    <form wire:submit="save" class="flex flex-col gap-5">

        {{-- Step Indicator --}}
        <div class="mb-8">

            <div class="flex items-center justify-center">

                @foreach([
                    1 => 'Personal & Residency Information',
                    2 => 'Education, Employment & Classification'
                ] as $number => $label)

                    <div class="flex items-center">

                        <button
                            type="button"
                            wire:click="$set('step', {{ $number }})"
                            class="flex flex-col items-center">

                            <div
                                class="
                                    w-10 h-10
                                    rounded-full
                                    flex items-center justify-center
                                    font-semibold
                                    transition

                                    {{ $step >= $number
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-gray-200 text-gray-500'
                                    }}
                                ">

                                {{ $number }}

                            </div>

                            <span class="mt-2 text-sm">
                                {{ $label }}
                            </span>

                        </button>

                        @if($number < 2)

                            <div
                                class="
                                    w-16 h-1 mx-3
                                    {{ $step > $number
                                        ? 'bg-blue-600'
                                        : 'bg-gray-200'
                                    }}
                                ">
                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        </div>

        @if($step === 1)

            <div
                class="rounded-xl border border-gray-200 bg-white
                    p-5 shadow-sm">

                {{-- ================================================= --}}
                {{-- SECTION HEADER --}}
                {{-- ================================================= --}}

                <div class="mb-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                rounded-lg bg-blue-50 text-blue-600"
                        >
                            <i class="fa-solid fa-id-card"></i>
                        </div>

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Personal Identification
                            </h3>

                            <p class="text-sm text-gray-500">
                                Basic personal information of the resident.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- FORM FIELDS --}}
                {{-- ================================================= --}}

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">


                    {{-- ================================================= --}}
                    {{-- FIRST NAME --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            First Name
                        </label>

                        <input
                            type="text"
                            wire:model="first_name"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('first_name')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- MIDDLE NAME --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Middle Name
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="text"
                            wire:model="middle_name"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('middle_name')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- LAST NAME --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Last Name
                        </label>

                        <input
                            type="text"
                            wire:model="last_name"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('last_name')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- EXTENSION NAME --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Extension Name
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="text"
                            wire:model="extension_name"
                            placeholder="Jr., Sr., III..."
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('extension_name')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- SEX --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Sex
                        </label>

                        <select
                            wire:model="sex"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                -- Select Sex --
                            </option>

                            <option value="Male">
                                Male
                            </option>

                            <option value="Female">
                                Female
                            </option>

                        </select>

                        @error('sex')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- DATE OF BIRTH --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Date of Birth
                        </label>

                        <input
                            type="date"
                            wire:model="birth_date"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- CIVIL STATUS --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Civil Status
                        </label>

                        <select
                            wire:model="civil_status"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                -- Select Civil Status --
                            </option>

                            <option value="Single">
                                Single
                            </option>

                            <option value="Married">
                                Married
                            </option>

                            <option value="Widow/Widower">
                                Widow/Widower
                            </option>

                            <option value="Divorced">
                                Divorced
                            </option>

                            <option value="Legally Separated">
                                Legally Separated
                            </option>

                        </select>

                        @error('civil_status')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- CITIZENSHIP --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Citizenship
                        </label>

                        <input
                            type="text"
                            wire:model="citizenship"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('citizenship')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- PLACE OF BIRTH --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Place of Birth
                        </label>

                        <input
                            type="text"
                            wire:model="place_of_birth"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('place_of_birth')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- CONTACT NUMBER --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Contact Number
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="text"
                            wire:model="contact_number"
                            placeholder="09XXXXXXXXX"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('contact_number')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- REGISTERED VOTER --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Registered Voter
                        </label>

                        <select
                            wire:model="registered_voter"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                -- Select --
                            </option>

                            <option value="Yes - within">
                                Yes - Within Barangay
                            </option>

                            <option value="Yes - elsewhere">
                                Yes - Elsewhere
                            </option>

                            <option value="No">
                                No
                            </option>

                        </select>

                        @error('registered_voter')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>

            <div
                class="rounded-xl border border-gray-200 bg-white
                    p-5 shadow-sm">

                <div class="mb-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                rounded-lg bg-blue-50 text-blue-600"
                        >
                            <i class="fa-solid fa-house"></i>
                        </div>

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Residency Information
                            </h3>

                            <p class="text-sm text-gray-500">
                                House ownership and residency information.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    {{-- House Ownership --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            House Ownership
                        </label>

                        <select
                            wire:model="house_ownership"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">-- Select --</option>

                            <option value="Owned">
                                Owned
                            </option>

                            <option value="Rented">
                                Rented
                            </option>

                            <option value="Living with relatives">
                                Living with relatives
                            </option>

                            <option value="Other">
                                Other
                            </option>
                        </select>

                        @error('house_ownership')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Relationship To Head --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Relationship to Head
                        </label>

                        <select
                            wire:model="relationship_to_head"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">-- Select --</option>
                            <option value="Head of Household">
                                Head of the Household
                            </option>
                            <option value="Spouse">
                                Spouse
                            </option>
                            <option value="Child">
                                Child
                            </option>
                            <option value="Parent">
                                Parent
                            </option>
                            <option value="Sibling">
                                Sibling
                            </option>
                            <option value="Renter">
                                Renter
                            </option>
                            <option value="Other">
                                Other
                            </option>
                        </select>

                        @error('relationship_to_head')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Residence Since --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Residence Since
                        </label>

                        <input
                            type="number"
                            wire:model="residence_since"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('residence_since')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
            
        @endif

        @if($step === 2)
            <div
                class="rounded-xl border border-gray-200 bg-white
                    p-5 shadow-sm">

                <div class="mb-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                rounded-lg bg-blue-50 text-blue-600"
                        >
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Education & Employment
                            </h3>

                            <p class="text-sm text-gray-500">
                                Educational background and employment information.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <div>
                        <label>Educational Attainment</label>

                        <select
                            wire:model="educational_attainment"
                            class="w-full rounded-lg border-gray-300">

                            <option value="">
                                --Select--
                            </option>

                            <option value="No Formal Education">No Formal Education</option>
                            <option value="Elementary Level">Elementary Level</option>
                            <option value="Elementary Graduate">Elementary Graduate</option>
                            <option value="High School Level">High School Level</option>
                            <option value="High School Graduate">High School Graduate</option>
                            <option value="Technical-Vocational">Technical-Vocational</option>
                            <option value="College Level">College Level</option>
                            <option value="College Graduate">College Graduate</option>
                            <option value="Postgraduate">Postgraduate</option>

                        </select>

                        @error('educational_attainment')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label>Out of School</label>

                        <select
                            wire:model="out_of_school"
                            class="w-full rounded-lg border-gray-300">

                            <option value="">
                                --Select--
                            </option>

                            <option value="Yes">Yes</option>
                            <option value="No">No</option>

                        </select>

                        @error('out_of_school')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label>Employment Status</label>

                        <select wire:model="employment_status" class="w-full rounded-lg border-gray-300">
                            <option value="">-- Select --</option>
                            <option value="Employed">Employed</option>
                            <option value="Self-employed">Self-employed</option>
                            <option value="Unemployed">Unemployed</option>
                            <option value="Student">Student</option>
                            <option value="Not in labor force">Not in labor force</option>
                            <option value="Other">Other</option>
                        </select>

                        @error('employment_status')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Educational Attainment --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Religion
                        </label>

                        <input type="text" wire:model="religion" class="w-full rounded-lg border-gray-300">

                        @error('religion')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div>
                        <label>Occupation</label>
                        <input type="text" wire:model="occupation" class="w-full rounded-lg border-gray-300">
                        @error('occupation') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label>OFW</label>
                        <select wire:model.live="is_ofw" class="w-full rounded-lg border-gray-300">
                            <option value="">-- Select --</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No / Not working abroad</option>
                        </select>
                        @error('is_ofw') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    @if($is_ofw == 'Yes')
                        <div>
                            <label>Country of Employment</label>
                            <input type="text" wire:model="ofw_country" class="w-full rounded-lg border-gray-300">
                            @error('ofw_country') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>

            </div>

            <div
                class="rounded-xl border border-gray-200
                    bg-white p-5 shadow-sm">

                <div class="mb-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                rounded-lg bg-emerald-50 text-emerald-600"
                        >
                            <i class="fa-solid fa-people-group"></i>
                        </div>

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Sectoral / Special Classification
                            </h3>

                            <p class="text-sm text-gray-500">
                                Select applicable classifications for this resident.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                    {{-- ================================================= --}}
                    {{-- PWD --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Person with Disability (PWD)
                        </label>

                        <select
                            wire:model="is_pwd"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">-- Select --</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>

                        </select>

                        @error('is_pwd')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- SOLO PARENT --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Solo Parent
                        </label>

                        <select
                            wire:model="is_solo_parent"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">-- Select --</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>

                        </select>

                        @error('is_solo_parent')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- INDIGENOUS PEOPLES --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Indigenous Peoples (IP)
                        </label>

                        <select
                            wire:model.live="is_indigenous"
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">-- Select --</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>

                        </select>

                        @error('is_indigenous')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- ETHNIC GROUP --}}
                    {{-- ================================================= --}}

                    @if($is_indigenous == 'Yes')

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ethnic Group
                            </label>

                            <input
                                type="text"
                                wire:model="indigenous_group"
                                placeholder="Enter ethnic group"
                                class="w-full rounded-lg border-gray-300
                                    focus:border-blue-500 focus:ring-blue-500"
                            >

                            @error('indigenous_group')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    @endif

                </div>

            </div>

        @endif

        <div class="mt-5 flex justify-between">
            @if($step > 1)

                <button
                    type="button"
                    wire:click="previousStep"
                    class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">

                    <i class="fa-solid fa-arrow-left mr-2"></i>

                    Previous

                </button>

            @else

                <div></div>

            @endif

            @if($step < 2)

                <button
                    type="button"
                    wire:click="nextStep"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">

                    Next

                    <i class="fa-solid fa-arrow-right ml-2"></i>

                </button>

            @else

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">

                    <span wire:loading.remove>
                        <i class="fa-solid fa-user-plus mr-2"></i>
                        Save Resident
                    </span>

                    <span wire:loading>
                        Saving...
                    </span>

                </button>

            @endif

        </div>

    </form>
</div>