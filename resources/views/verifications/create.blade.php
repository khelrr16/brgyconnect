<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">
                Account Verification
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Submit your resident information so the barangay can verify your account.
            </p>
        </div>
    </x-slot>


    <div class="py-10">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- ================================================= --}}
            {{-- INFORMATION NOTICE --}}
            {{-- ================================================= --}}

            <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4">

                <div class="flex gap-3">

                    <i class="fa-solid fa-circle-info mt-0.5 text-blue-600"></i>

                    <div class="text-sm text-blue-800">

                        <p class="font-semibold">
                            Why do we need this information?
                        </p>

                        <p class="mt-1 leading-6">
                            The information you provide will be compared with the
                            official resident records maintained by the barangay.
                            This allows the administrator to link your online
                            account to your resident record.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ================================================= --}}

            @if ($errors->any())

                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">

                    <div class="flex gap-3">

                        <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-600"></i>

                        <div>

                            <p class="text-sm font-semibold text-red-800">
                                Please correct the following errors:
                            </p>

                            <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- FORM CARD --}}
            {{-- ================================================= --}}

            <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200">

                {{-- Card Header --}}

                <div class="border-b border-gray-100 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-lg bg-indigo-100 text-indigo-600"
                        >
                            <i class="fa-solid fa-user-check"></i>
                        </div>

                        <div>

                            <h3 class="text-lg font-bold text-gray-900">
                                Resident Information
                            </h3>

                            <p class="text-sm text-gray-500">
                                Enter your information exactly as registered.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Form --}}

                <form
                    method="POST"
                    action="{{ route('verifications.store') }}"
                    class="px-6 py-6"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- NAME --}}
                    {{-- ================================================= --}}

                    <div class="mb-8">

                        <h4 class="mb-4 text-sm font-bold uppercase
                                   tracking-wider text-gray-700">

                            Personal Information

                        </h4>


                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- First Name --}}

                            <div>

                                <x-input-label
                                    for="first_name"
                                    value="First Name"
                                />

                                <x-text-input
                                    id="first_name"
                                    name="first_name"
                                    type="text"
                                    class="mt-1.5 block w-full"
                                    value="{{ old('first_name') }}"
                                    required
                                    autofocus
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('first_name')"
                                />

                            </div>


                            {{-- Middle Name --}}

                            <div>

                                <x-input-label
                                    for="middle_name"
                                    value="Middle Name"
                                />

                                <x-text-input
                                    id="middle_name"
                                    name="middle_name"
                                    type="text"
                                    class="mt-1.5 block w-full"
                                    value="{{ old('middle_name') }}"
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('middle_name')"
                                />

                            </div>


                            {{-- Last Name --}}

                            <div>

                                <x-input-label
                                    for="last_name"
                                    value="Last Name"
                                />

                                <x-text-input
                                    id="last_name"
                                    name="last_name"
                                    type="text"
                                    class="mt-1.5 block w-full"
                                    value="{{ old('last_name') }}"
                                    required
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('last_name')"
                                />

                            </div>


                            {{-- Extension Name --}}

                            <div>

                                <x-input-label
                                    for="extension_name"
                                    value="Extension Name"
                                />

                                <select
                                    id="extension_name"
                                    name="extension_name"
                                    class="mt-1.5 block w-full rounded-lg
                                           border-gray-300
                                           shadow-sm
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                >

                                    <option value="">
                                        None
                                    </option>

                                    <option
                                        value="Jr."
                                        {{ old('extension_name') === 'Jr.' ? 'selected' : '' }}
                                    >
                                        Jr.
                                    </option>

                                    <option
                                        value="Sr."
                                        {{ old('extension_name') === 'Sr.' ? 'selected' : '' }}
                                    >
                                        Sr.
                                    </option>

                                    <option
                                        value="II"
                                        {{ old('extension_name') === 'II' ? 'selected' : '' }}
                                    >
                                        II
                                    </option>

                                    <option
                                        value="III"
                                        {{ old('extension_name') === 'III' ? 'selected' : '' }}
                                    >
                                        III
                                    </option>

                                    <option
                                        value="IV"
                                        {{ old('extension_name') === 'IV' ? 'selected' : '' }}
                                    >
                                        IV
                                    </option>

                                </select>

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('extension_name')"
                                />

                            </div>

                            {{-- Birthday --}}

                            <div class="sm:col-span-2">

                                <x-input-label
                                    for="birth_date"
                                    value="Birthday"
                                />

                                <x-text-input
                                    id="birth_date"
                                    name="birth_date"
                                    type="date"
                                    class="mt-1.5 block w-full sm:w-1/2"
                                    value="{{ old('birth_date') }}"
                                    required
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('birth_date')"
                                />

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- ADDRESS --}}
                    {{-- ================================================= --}}

                    <div>

                        <h4 class="mb-4 text-sm font-bold uppercase
                                   tracking-wider text-gray-700">

                            Residential Address

                        </h4>


                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Block --}}

                            <div>

                                <x-input-label
                                    for="block"
                                    value="Block"
                                />

                                <x-text-input
                                    id="block"
                                    name="block"
                                    type="number"
                                    class="mt-1.5 block w-full"
                                    value="{{ old('block') }}"
                                    placeholder="Enter block number"
                                    required
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('block')"
                                />

                            </div>


                            {{-- Lot --}}

                            <div>

                                <x-input-label
                                    for="lot"
                                    value="Lot"
                                />

                                <x-text-input
                                    id="lot"
                                    name="lot"
                                    type="text"
                                    class="mt-1.5 block w-full"
                                    value="{{ old('lot') }}"
                                    placeholder="Enter lot number"
                                    required
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('lot')"
                                />

                            </div>

                            {{-- Unit --}}

                            <div>

                                <x-input-label
                                    for="unit"
                                    value="Unit (Optional)"
                                />

                                <x-text-input
                                    id="unit"
                                    name="unit"
                                    type="text"
                                    class="mt-1.5 block w-full"
                                    value="{{ old('unit') }}"
                                    placeholder="Enter unit number"
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('unit')"
                                />

                            </div>


                            {{-- Street --}}

                            <div>

                                <x-input-label
                                    for="street"
                                    value="Street"
                                />

                                <x-text-input
                                    id="street"
                                    name="street"
                                    type="text"
                                    class="mt-1.5 block w-full"
                                    value="{{ old('street') }}"
                                    placeholder="(E.g Main St., 1st Ave., etc.)"
                                    required
                                />

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('street')"
                                />

                            </div>


                            {{-- Subdivision --}}

                            <div>

                                <x-input-label
                                    for="subdivision"
                                    value="Subdivision"
                                />

                                <select
                                    id="subdivision"
                                    name="subdivision"
                                    class="mt-1 w-full rounded-lg border-gray-300">

                                    <option value="">
                                        --Select--
                                    </option>

                                    <option 
                                        value="Conpil I Village" 
                                        {{ old('subdivision') === 'Conpil I Village' ? 'selected' : '' }}
                                    >
                                        Conpil I Village
                                    </option>

                                    <option 
                                        value="Conpil III Executive"
                                        {{ old('subdivision') === 'Conpil III Executive' ? 'selected' : '' }}
                                    >
                                        Conpil III Executive
                                    </option>

                                    <option 
                                        value="Console 1 Village"
                                        {{ old('subdivision') === 'Console 1 Village' ? 'selected' : '' }}
                                    >
                                        Console 1 Village
                                    </option>

                                    <option 
                                        value="Greatland Village"
                                        {{ old('subdivision') === 'Greatland Village' ? 'selected' : '' }}
                                    >
                                        Greatland Village
                                    </option>

                                    <option 
                                        value="Guevara Subdivision"
                                        {{ old('subdivision') === 'Guevara Subdivision' ? 'selected' : '' }}
                                    >
                                        Guevara Subdivision
                                    </option>

                                    <option 
                                        value="Pacita 2A"
                                        {{ old('subdivision') === 'Pacita 2A' ? 'selected' : '' }}
                                    >
                                        Pacita 2A
                                    </option>

                                    <option 
                                        value="Pacita 2B"
                                        {{ old('subdivision') === 'Pacita 2B' ? 'selected' : '' }}
                                    >
                                        Pacita 2B
                                    </option>

                                </select>

                                <x-input-error
                                    class="mt-1"
                                    :messages="$errors->get('subdivision')"
                                />

                            </div>
                        </div>
                    </div>


                    {{-- ================================================= --}}
                    {{-- SUBMIT --}}
                    {{-- ================================================= --}}

                    <div class="mt-8 flex items-center justify-end
                                border-t border-gray-100 pt-6">

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2
                                   rounded-lg
                                   border border-indigo-700
                                   bg-indigo-600
                                   px-5 py-2.5
                                   text-sm font-semibold
                                   text-white
                                   shadow-sm
                                   hover:bg-indigo-700
                                   hover:shadow-md
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500
                                   focus:ring-offset-2
                                   transition"
                        >

                            <i class="fa-solid fa-paper-plane"></i>

                            Submit for Verification

                        </button>

                    </div>

                </form>

            </div>


            {{-- ================================================= --}}
            {{-- FOOTNOTE --}}
            {{-- ================================================= --}}

            <div class="mt-4 flex items-start gap-2 px-1 text-xs text-gray-500">

                <i class="fa-solid fa-shield-halved mt-0.5"></i>

                <p>
                    Your information will be reviewed by an authorized
                    barangay administrator. Submission does not automatically
                    create a resident record.
                </p>

            </div>

        </div>

    </div>

</x-app-layout>