<?php

use App\Models\Resident;
use Livewire\Component;

new class extends Component
{
    public ?Resident $resident = null;

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

    // =========================
    // RESIDENCY INFORMATION
    // =========================

    public string $block = '';
    public string $lot = '';
    public string $unit = '';
    public string $street = '';
    public string $subdivision = '';

    public string $house_ownership = '';
    public string $relationship_to_head = '';
    public string $residence_since = '';

    // =========================
    // SOCIO-ECONOMIC DATA
    // =========================

    public string $educational_attainment = '';
    public string $employment_status = '';
    public string $religion = '';
    public string $occupation = '';
    public ?string $monthly_income = null;

    // =========================
    // EMERGENCY CONTACT
    // =========================

    public string $emergency_contact_name = '';
    public ?string $emergency_contact_number = '';

    public function mount(): void
    {
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
        $this->block = $this->resident->block ?? '';
        $this->lot = $this->resident->lot ?? '';
        $this->unit = $this->resident->unit ?? '';
        $this->street = $this->resident->street ?? '';
        $this->subdivision = $this->resident->subdivision ?? '';
        $this->house_ownership = $this->resident->house_ownership ?? '';
        $this->relationship_to_head = $this->resident->relationship_to_head ?? '';
        $this->residence_since = (string) ($this->resident->residence_since ?? '');
        $this->educational_attainment = $this->resident->educational_attainment ?? '';
        $this->employment_status = $this->resident->employment_status ?? '';
        $this->religion = $this->resident->religion ?? '';
        $this->occupation = $this->resident->occupation ?? '';
        $this->monthly_income = $this->resident->monthly_income !== null
            ? (string) $this->resident->monthly_income
            : null;
        $this->emergency_contact_name = $this->resident->emergency_contact_name ?? '';
        $this->emergency_contact_number = $this->resident->emergency_contact_number ?? '';
    }

    // =========================
    // VALIDATION
    // =========================

    protected function rules(): array
    {
        return [

            // Personal
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'extension_name' => ['nullable', 'string', 'max:50'],

            'sex' => [
                'required',
                'in:Male,Female,Other'
            ],

            'birth_date' => [
                'required',
                'date'
            ],

            'civil_status' => [
                'required',
                'in:Single,Married,Widow/Widower,Legally Separated'
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

            // Residency
            'block' => ['required', 'string', 'max:50'],
            'lot' => ['required', 'string', 'max:50'],
            'unit' => ['nullable', 'string', 'max:50'],

            'street' => [
                'required',
                'string',
                'max:255'
            ],

            'subdivision' => [
                'required',
                'string',
                'max:255'
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

            'monthly_income' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'emergency_contact_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'emergency_contact_number' => [
                'nullable',
                'string',
                'max:20'
            ],
        ];
    }

    public function updated($property): void
    {
        $this->sanitizeField($property);
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
            'block',
            'lot',
            'unit',
            'street',
            'subdivision',
            'religion',
            'occupation',
        ];

        $numericFields = [
            'contact_number',
            'residence_since',
            'monthly_income',
            'emergency_contact_number',
        ];

        $nullableNumericFields = [
            'contact_number',
            'monthly_income',
            'emergency_contact_number',
        ];

        if (in_array($property, $textFields, true)) {
            $this->{$property} = preg_replace(
                '/[^\pL\pN\s]/u',
                '',
                (string) $this->{$property}
            );
        }

        if (in_array($property, $numericFields, true)) {
            if (trim((string) $this->{$property}) === '') {
                if (in_array($property, $nullableNumericFields, true)) {
                    $this->{$property} = null;
                }

                return;
            }

            $value = preg_replace('/[^0-9.]/', '', (string) $this->{$property});
            $parts = explode('.', $value, 2);
            $this->{$property} = $parts[0]
                . (isset($parts[1]) ? '.' . str_replace('.', '', $parts[1]) : '');
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
            'block',
            'lot',
            'unit',
            'street',
            'subdivision',
            'religion',
            'occupation',
            'contact_number',
            'residence_since',
            'monthly_income',
            'emergency_contact_name',
            'emergency_contact_number',
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

        if ($this->step < 4) {
            $this->step++;
        }
    }

    // =========================
    // PREVIOUS STEP
    // =========================

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
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
                'block',
                'lot',
                'unit',
                'street',
                'subdivision',
                'house_ownership',
                'relationship_to_head',
                'residence_since',
            ],

            3 => [
                'educational_attainment',
                'employment_status',
                'religion',
                'occupation',
                'monthly_income',
            ],

            4 => [
                'emergency_contact_name',
                'emergency_contact_number',
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
            $validated['resident_id'] =
                'RES-' . str_pad(
                    Resident::count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );

            Resident::create($validated);
            $message = 'Resident registered successfully.';
        }

        session()->flash(
            'success',
            $message
        );

        return $this->redirectRoute('residents.index');
    }

    // =========================
    // FIND STEP WITH ERROR
    // =========================

    protected function stepForError(string $field): int
    {
        $personal = [
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
        ];

        $residency = [
            'block',
            'lot',
            'unit',
            'street',
            'subdivision',
            'house_ownership',
            'relationship_to_head',
            'residence_since',
        ];

        if (in_array($field, $personal)) {
            return 1;
        }

        if (in_array($field, $residency)) {
            return 2;
        }

        return 3;
    }
};
?>

<div>

    <form wire:submit="save">

    {{-- Step Indicator --}}
    <div class="mb-8">

        <div class="flex items-center justify-center">

            @foreach([
                1 => 'Personal Identification',
                2 => 'Residency Information',
                3 => 'Socio-Economic Data',
                4 => 'Emergency Contact'
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

                    @if($number < 4)

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

        <div>

            <h2 class="text-2xl font-bold mb-6">
                Personal Identification
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- First Name --}}
                <div>
                    <label>First Name</label>

                    <input
                        type="text"
                        wire:model="first_name"
                        class="w-full rounded-lg border-gray-300">

                    @error('first_name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Middle Name --}}
                <div>
                    <label>Middle Name</label>

                    <input
                        type="text"
                        wire:model="middle_name"
                        class="w-full rounded-lg border-gray-300">

                    @error('middle_name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div>
                    <label>Last Name</label>

                    <input
                        type="text"
                        wire:model="last_name"
                        class="w-full rounded-lg border-gray-300">

                    @error('last_name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Extension --}}
                <div>
                    <label>Extension Name <small class="text-gray-500">(Optional)</small></label>

                    <input
                        type="text"
                        wire:model="extension_name"
                        placeholder="Jr., Sr., III..."
                        class="w-full rounded-lg border-gray-300">

                    @error('extension_name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Sex --}}
                <div>
                    <label>Sex</label>

                    <select
                        wire:model="sex"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">--Select Sex--</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>

                    </select>

                    @error('sex')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Birthday --}}
                <div>
                    <label>Date of Birth</label>

                    <input
                        type="date"
                        wire:model="birth_date"
                        class="w-full rounded-lg border-gray-300">

                    @error('birth_date')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Civil Status --}}
                <div>
                    <label>Civil Status</label>

                    <select
                        wire:model="civil_status"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">--Select Civil Status--</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widow/Widower">
                            Widow/Widower
                        </option>
                        <option value="Legally Separated">
                            Legally Separated
                        </option>

                    </select>

                    @error('civil_status')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Citizenship --}}
                <div>
                    <label>Citizenship</label>

                    <input
                        type="text"
                        wire:model="citizenship"
                        class="w-full rounded-lg border-gray-300">

                    @error('citizenship')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Place of Birth --}}
                <div>
                    <label>Place of Birth</label>

                    <input
                        type="text"
                        wire:model="place_of_birth"
                        class="w-full rounded-lg border-gray-300">

                    @error('place_of_birth')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Contact --}}
                <div>
                    <label>Contact Number</label>

                    <input
                        type="text"
                        wire:model="contact_number"
                        class="w-full rounded-lg border-gray-300">

                    @error('contact_number')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Voter --}}
                <div>
                    <label>Registered Voter</label>

                    <select
                        wire:model="registered_voter"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">
                            --Select--
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
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

        </div>

    @endif

    @if($step === 2)

        <div>

            <h2 class="text-2xl font-bold mb-6">
                Residency Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label>Block</label>

                    <input
                        type="text"
                        wire:model="block"
                        class="w-full rounded-lg border-gray-300">

                    @error('block')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label>Lot</label>

                    <input
                        type="text"
                        wire:model="lot"
                        class="w-full rounded-lg border-gray-300">

                    @error('lot')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label>Unit <small class="text-gray-500">(Optional)</small></label>

                    <input
                        type="text"
                        wire:model="unit"
                        class="w-full rounded-lg border-gray-300">

                    @error('unit')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                <div>
                    <label>Street</label>

                    <input
                        placeholder="Lumpia St., Adobo Ave., Sinigang Blvd."
                        type="text"
                        wire:model="street"
                        class="w-full rounded-lg border-gray-300">

                    @error('street')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label>Subdivision</label>

                    <select
                        wire:model="subdivision"
                        class="w-full rounded-lg border-gray-300">

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
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label>House Ownership</label>

                    <select
                        wire:model="house_ownership"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">
                            --Select--
                        </option>

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
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label>Relationship to Head</label>

                    <select
                        wire:model="relationship_to_head"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">
                            --Select--
                        </option>

                        <option value="Head of Household">
                            Head of Household
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

                        <option value="Other">
                            Other
                        </option>

                    </select>

                    @error('relationship_to_head')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label>Residence Since</label>

                    <input
                        type="number"
                        wire:model="residence_since"
                        placeholder="Year"
                        class="w-full rounded-lg border-gray-300">

                    @error('residence_since')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

        </div>

    @endif

    @if($step === 3)

        <div>

            <h2 class="text-2xl font-bold mb-6">
                Socio-Economic Data
            </h2>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label>Educational Attainment</label>

                    <select
                        wire:model="educational_attainment"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">--Select--</option>

                        <option value="No Formal Education">
                            No Formal Education
                        </option>

                        <option value="Elementary Level">
                            Elementary Level
                        </option>

                        <option value="Elementary Graduate">
                            Elementary Graduate
                        </option>

                        <option value="High School Level">
                            High School Level
                        </option>

                        <option value="High School Graduate">
                            High School Graduate
                        </option>

                        <option value="Technical-Vocational">
                            Technical-Vocational
                        </option>

                        <option value="College Level">
                            College Level
                        </option>

                        <option value="College Graduate">
                            College Graduate
                        </option>

                        <option value="Postgraduate">
                            Postgraduate
                        </option>

                    </select>

                    @error('educational_attainment')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label>Employment Status</label>

                    <select
                        wire:model="employment_status"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">--Select--</option>

                        <option value="Employed">
                            Employed
                        </option>

                        <option value="Self-Employed">
                            Self-Employed
                        </option>

                        <option value="Unemployed">
                            Unemployed
                        </option>

                        <option value="Student">
                            Student
                        </option>

                        <option value="Retired">
                            Retired
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                    @error('employment_status')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label>Religion</label>

                    <input
                        type="text"
                        wire:model="religion"
                        class="w-full rounded-lg border-gray-300">

                    @error('religion')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label>Occupation</label>

                    <input
                        type="text"
                        wire:model="occupation"
                        class="w-full rounded-lg border-gray-300">

                    @error('occupation')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label>Monthly Income</label>

                    <div class="flex">

                        <span class="px-3 py-2 bg-gray-100 border border-r-0 rounded-l-lg">
                            ₱
                        </span>

                        <input
                            type="number"
                            step="0.01"
                            wire:model="monthly_income"
                            class="w-full rounded-r-lg border-gray-300">

                        @error('monthly_income')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

        </div>

    @endif

    @if($step === 4)
    
        <div>

            <h2 class="text-2xl font-bold mb-6">
                Emergency Contact
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label>Emergency Contact Name</label>

                    <input
                        type="text"
                        wire:model="emergency_contact_name"
                        class="w-full rounded-lg border-gray-300">

                    @error('emergency_contact_name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label>Emergency Contact Number</label>

                    <input
                        type="text"
                        wire:model="emergency_contact_number"
                        class="w-full rounded-lg border-gray-300">

                    @error('emergency_contact_number')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

        </div>
    @endif

    <div class="mt-8 flex justify-between">

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

        @if($step < 4)

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