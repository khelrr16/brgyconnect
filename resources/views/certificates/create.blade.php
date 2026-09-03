<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium
                           text-gray-500 transition hover:text-indigo-600"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Home
                </a>

                <div class="mt-5">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-xl bg-indigo-50 text-indigo-600"
                        >
                            <i class="fa-solid fa-file-certificate text-lg"></i>
                        </div>

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                                Barangay Services
                            </p>

                            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                                Request a Certificate
                            </h1>

                        </div>

                    </div>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-gray-500">
                        Select the certificate you need and provide the required
                        information. Your request will be reviewed by the
                        barangay office.
                    </p>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- FORM --}}
        {{-- ================================================= --}}

        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

            <form
                method="POST"
                action="{{ route('certificate.requests.store') }}"
                class="space-y-6"
            >

                @csrf


                {{-- ================================================= --}}
                {{-- APPLICANT INFORMATION --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white shadow-sm"
                >

                    <div class="border-b border-gray-200 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-blue-50 text-blue-600"
                            >
                                <i class="fa-solid fa-user"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Applicant Information
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Information from your verified resident account.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid gap-5 px-6 py-6 sm:grid-cols-2">

                        {{-- Full Name --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Full Name
                            </label>

                            <div
                                class="mt-1 rounded-lg border border-gray-200
                                       bg-gray-50 px-4 py-2.5 text-sm text-gray-700"
                            >
                                {{ auth()->user()->resident?->full_name ?? auth()->user()->name }}
                            </div>

                        </div>


                        {{-- Resident ID --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Resident ID
                            </label>

                            <div
                                class="mt-1 rounded-lg border border-gray-200
                                       bg-gray-50 px-4 py-2.5 text-sm text-gray-700"
                            >
                                {{ auth()->user()->resident?->resident_id ?? '—' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CERTIFICATE TYPE --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white shadow-sm"
                >

                    <div class="border-b border-gray-200 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-indigo-50 text-indigo-600"
                            >
                                <i class="fa-solid fa-file-lines"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Certificate Type
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Select the certificate you want to request.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="px-6 py-6">

                        <div class="grid gap-4 sm:grid-cols-2">


                            {{-- Certificate of Indigency --}}
                            <label class="group cursor-pointer">

                                <input
                                    type="radio"
                                    name="certificate_type"
                                    value="Certificate of Indigency"
                                    class="peer sr-only"
                                    {{ old('certificate_type') === 'Certificate of Indigency' ? 'checked' : '' }}
                                >

                                <div
                                    class="rounded-xl border border-gray-200 p-4
                                           transition
                                           peer-checked:border-indigo-500
                                           peer-checked:bg-indigo-50
                                           peer-checked:ring-2
                                           peer-checked:ring-indigo-100
                                           group-hover:border-indigo-300"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center
                                                   justify-center rounded-lg
                                                   bg-indigo-50 text-indigo-600"
                                        >
                                            <i class="fa-solid fa-hand-holding-heart"></i>
                                        </div>

                                        <div>

                                            <h3 class="text-sm font-bold text-gray-900">
                                                Certificate of Indigency
                                            </h3>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Certifies that a resident belongs
                                                to an indigent household.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </label>


                            {{-- Certificate of Residency --}}
                            <label class="group cursor-pointer">

                                <input
                                    type="radio"
                                    name="certificate_type"
                                    value="Certificate of Residency"
                                    class="peer sr-only"
                                    {{ old('certificate_type') === 'Certificate of Residency' ? 'checked' : '' }}
                                >

                                <div
                                    class="rounded-xl border border-gray-200 p-4
                                           transition
                                           peer-checked:border-indigo-500
                                           peer-checked:bg-indigo-50
                                           peer-checked:ring-2
                                           peer-checked:ring-indigo-100
                                           group-hover:border-indigo-300"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center
                                                   justify-center rounded-lg
                                                   bg-emerald-50 text-emerald-600"
                                        >
                                            <i class="fa-solid fa-house-user"></i>
                                        </div>

                                        <div>

                                            <h3 class="text-sm font-bold text-gray-900">
                                                Certificate of Residency
                                            </h3>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Certifies that the applicant is a
                                                resident of the barangay.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </label>


                            {{-- Certificate of Good Moral --}}
                            <label class="group cursor-pointer">

                                <input
                                    type="radio"
                                    name="certificate_type"
                                    value="Certificate of Good Moral"
                                    class="peer sr-only"
                                    {{ old('certificate_type') === 'Certificate of Good Moral' ? 'checked' : '' }}
                                >

                                <div
                                    class="rounded-xl border border-gray-200 p-4
                                           transition
                                           peer-checked:border-indigo-500
                                           peer-checked:bg-indigo-50
                                           peer-checked:ring-2
                                           peer-checked:ring-indigo-100
                                           group-hover:border-indigo-300"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center
                                                   justify-center rounded-lg
                                                   bg-amber-50 text-amber-600"
                                        >
                                            <i class="fa-solid fa-medal"></i>
                                        </div>

                                        <div>

                                            <h3 class="text-sm font-bold text-gray-900">
                                                Certificate of Good Moral
                                            </h3>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Used for employment, school,
                                                or other official purposes.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </label>


                            {{-- Certificate of Non-Employment --}}
                            <label class="group cursor-pointer">

                                <input
                                    type="radio"
                                    name="certificate_type"
                                    value="Certificate of Non-Employment"
                                    class="peer sr-only"
                                    {{ old('certificate_type') === 'Certificate of Non-Employment' ? 'checked' : '' }}
                                >

                                <div
                                    class="rounded-xl border border-gray-200 p-4
                                           transition
                                           peer-checked:border-indigo-500
                                           peer-checked:bg-indigo-50
                                           peer-checked:ring-2
                                           peer-checked:ring-indigo-100
                                           group-hover:border-indigo-300"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center
                                                   justify-center rounded-lg
                                                   bg-red-50 text-red-600"
                                        >
                                            <i class="fa-solid fa-briefcase"></i>
                                        </div>

                                        <div>

                                            <h3 class="text-sm font-bold text-gray-900">
                                                Certificate of Non-Employment
                                            </h3>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Certifies the applicant's
                                                non-employment status.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </label>


                            {{-- Other --}}
                            <label class="group cursor-pointer sm:col-span-2">

                                <input
                                    type="radio"
                                    name="certificate_type"
                                    value="Other"
                                    class="peer sr-only"
                                    {{ old('certificate_type') === 'Other' ? 'checked' : '' }}
                                >

                                <div
                                    class="rounded-xl border border-gray-200 p-4
                                           transition
                                           peer-checked:border-indigo-500
                                           peer-checked:bg-indigo-50
                                           peer-checked:ring-2
                                           peer-checked:ring-indigo-100
                                           group-hover:border-indigo-300"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center
                                                   justify-center rounded-lg
                                                   bg-gray-100 text-gray-600"
                                        >
                                            <i class="fa-solid fa-file-circle-question"></i>
                                        </div>

                                        <div>

                                            <h3 class="text-sm font-bold text-gray-900">
                                                Other Certificate
                                            </h3>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Request another certificate not
                                                listed above.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </label>

                        </div>


                        @error('certificate_type')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REQUEST DETAILS --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white shadow-sm"
                >

                    <div class="border-b border-gray-200 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-amber-50 text-amber-600"
                            >
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Request Details
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Tell us why you need the certificate.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="space-y-5 px-6 py-6">


                        {{-- Purpose --}}
                        <div>

                            <label
                                for="purpose"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Purpose
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="purpose"
                                name="purpose"
                                rows="4"
                                placeholder="State the purpose of your certificate request..."
                                class="mt-1 block w-full rounded-lg border-gray-300
                                       shadow-sm focus:border-indigo-500
                                       focus:ring-indigo-500"
                            >{{ old('purpose') }}</textarea>

                            @error('purpose')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Additional Information --}}
                        <div>

                            <label
                                for="additional_information"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Additional Information
                                <span class="font-normal text-gray-400">
                                    (Optional)
                                </span>
                            </label>

                            <textarea
                                id="additional_information"
                                name="additional_information"
                                rows="3"
                                placeholder="Provide any additional details that may help process your request..."
                                class="mt-1 block w-full rounded-lg border-gray-300
                                       shadow-sm focus:border-indigo-500
                                       focus:ring-indigo-500"
                            >{{ old('additional_information') }}</textarea>

                            @error('additional_information')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PROCESSING NOTICE --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-blue-200
                           bg-blue-50 p-5"
                >

                    <div class="flex items-start gap-3">

                        <i
                            class="fa-solid fa-circle-info mt-0.5
                                   text-blue-600"
                        ></i>

                        <div>

                            <h3 class="text-sm font-semibold text-blue-900">
                                Before submitting
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-blue-700">
                                Please make sure that the information you
                                provide is accurate. Your request will be
                                reviewed by an authorized barangay official
                                before the certificate is issued.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ACTIONS --}}
                {{-- ================================================= --}}

                <div
                    class="flex flex-col-reverse gap-3 sm:flex-row
                           sm:justify-end"
                >

                    <a
                        href="{{ route('home') }}"
                        class="inline-flex items-center justify-center gap-2
                               rounded-lg border border-gray-300 bg-white
                               px-5 py-2.5 text-sm font-semibold text-gray-700
                               transition hover:bg-gray-50"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2
                               rounded-lg bg-indigo-600 px-5 py-2.5
                               text-sm font-semibold text-white shadow-sm
                               transition hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-paper-plane"></i>
                        Submit Request
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>