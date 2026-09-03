<x-app-layout>

    <div
        class="min-h-screen bg-gray-50"
        x-data="{
            incomeSource: @js(old('income_source', '')),
        }"
    >

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
                            <i class="fa-solid fa-hand-holding-heart text-lg"></i>
                        </div>

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                                Barangay Services
                            </p>

                            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                                Request Assistance Certificate
                            </h1>

                        </div>

                    </div>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-gray-500 sm:text-base">
                        Provide the information below so the barangay can
                        evaluate and process your assistance certificate request.
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
                action="{{ route('certificate.assistance.store') }}"
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
                                    Your verified resident information.
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
                {{-- SOURCE OF INCOME --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white shadow-sm"
                >

                    <div class="border-b border-gray-200 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-emerald-50 text-emerald-600"
                            >
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Source of Income
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Tell us about your current source of income.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="px-6 py-6">

                        {{-- Income Source --}}
                        <div>

                            <label
                                for="income_source"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Source of Income
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="income_source"
                                name="income_source"
                                x-model="incomeSource"
                                class="mt-1 block w-full rounded-lg border-gray-300
                                       shadow-sm focus:border-indigo-500
                                       focus:ring-indigo-500"
                            >

                                <option value="">
                                    -- Select Source of Income --
                                </option>

                                <option value="None">
                                    No Source of Income
                                </option>

                                <option value="Occupation">
                                    Employment / Occupation
                                </option>

                                <option value="Business">
                                    Business
                                </option>

                            </select>

                            @error('income_source')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- OCCUPATION --}}
                        {{-- ================================================= --}}

                        <div
                            x-show="incomeSource === 'Occupation'"
                            x-cloak
                            class="mt-5 rounded-xl border border-blue-100
                                   bg-blue-50/50 p-5"
                        >

                            <div class="mb-4 flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9 items-center justify-center
                                           rounded-lg bg-blue-100 text-blue-600"
                                >
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>

                                <div>

                                    <h3 class="text-sm font-semibold text-gray-900">
                                        Employment / Occupation
                                    </h3>

                                    <p class="text-xs text-gray-500">
                                        Provide your occupation and monthly income.
                                    </p>

                                </div>

                            </div>


                            <div class="grid gap-5 sm:grid-cols-2">

                                {{-- Occupation --}}
                                <div>

                                    <label
                                        for="occupation"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Occupation
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="occupation"
                                        id="occupation"
                                        value="{{ old('occupation') }}"
                                        placeholder="e.g. Teacher, Driver, Carpenter"
                                        class="mt-1 block w-full rounded-lg border-gray-300
                                               shadow-sm focus:border-indigo-500
                                               focus:ring-indigo-500"
                                    >

                                    @error('occupation')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- Monthly Income --}}
                                <div>

                                    <label
                                        for="monthly_income"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Monthly Income
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative mt-1">

                                        <span
                                            class="pointer-events-none absolute inset-y-0
                                                   left-0 flex items-center pl-3
                                                   text-sm text-gray-500"
                                        >
                                            ₱
                                        </span>

                                        <input
                                            type="number"
                                            name="monthly_income"
                                            id="monthly_income"
                                            value="{{ old('monthly_income') }}"
                                            min="0"
                                            step="0.01"
                                            placeholder="0.00"
                                            class="block w-full rounded-lg border-gray-300
                                                   pl-8 shadow-sm
                                                   focus:border-indigo-500
                                                   focus:ring-indigo-500"
                                        >

                                    </div>

                                    @error('monthly_income')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- BUSINESS --}}
                        {{-- ================================================= --}}

                        <div
                            x-show="incomeSource === 'Business'"
                            x-cloak
                            class="mt-5 rounded-xl border border-amber-100
                                   bg-amber-50/50 p-5"
                        >

                            <div class="mb-4 flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9 items-center justify-center
                                           rounded-lg bg-amber-100 text-amber-600"
                                >
                                    <i class="fa-solid fa-store"></i>
                                </div>

                                <div>

                                    <h3 class="text-sm font-semibold text-gray-900">
                                        Business Information
                                    </h3>

                                    <p class="text-xs text-gray-500">
                                        Provide information about your business.
                                    </p>

                                </div>

                            </div>


                            <div class="grid gap-5 sm:grid-cols-2">

                                {{-- Business --}}
                                <div>

                                    <label
                                        for="business_name"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Type of Business
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="business_name"
                                        id="business_name"
                                        value="{{ old('business_name') }}"
                                        placeholder="e.g. Sari-sari Store"
                                        class="mt-1 block w-full rounded-lg border-gray-300
                                               shadow-sm focus:border-indigo-500
                                               focus:ring-indigo-500"
                                    >

                                    @error('business_name')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- How Long --}}
                                <div>

                                    <label
                                        for="business_duration"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        How Long in Business?
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex gap-2">

                                        <input
                                            type="number"
                                            name="business_duration"
                                            id="business_duration"
                                            value="{{ old('business_duration') }}"
                                            min="0"
                                            placeholder="e.g. 5"
                                            class="mt-1 block w-full rounded-lg border-gray-300
                                                   shadow-sm focus:border-indigo-500
                                                   focus:ring-indigo-500"
                                        >

                                        <div
                                            class="mt-1 flex items-center rounded-lg
                                                   border border-gray-300 bg-gray-50
                                                   px-3 text-sm text-gray-500"
                                        >
                                            years
                                        </div>

                                    </div>

                                    @error('business_duration')
                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- No Income Notice --}}
                        <div
                            x-show="incomeSource === 'None'"
                            x-cloak
                            class="mt-5 rounded-xl border border-gray-200
                                   bg-gray-50 p-4"
                        >

                            <div class="flex items-start gap-3">

                                <i
                                    class="fa-solid fa-circle-info mt-0.5
                                           text-gray-500"
                                ></i>

                                <p class="text-sm leading-6 text-gray-600">
                                    You have indicated that you currently have
                                    no source of income.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ASSISTANCE REQUIREMENT --}}
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
                                <i class="fa-solid fa-list-check"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Assistance Requirement
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Select the type of assistance you are requesting.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="px-6 py-6">

                        <label
                            for="assistance_type"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Requirement
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="assistance_type"
                            id="assistance_type"
                            class="mt-1 block w-full rounded-lg border-gray-300
                                   shadow-sm focus:border-indigo-500
                                   focus:ring-indigo-500"
                        >

                            <option value="">
                                -- Select Assistance --
                            </option>

                            <option
                                value="Educational Assistance"
                                @selected(old('assistance_type') === 'Educational Assistance')
                            >
                                Educational Assistance
                            </option>

                            <option
                                value="Financial Assistance"
                                @selected(old('assistance_type') === 'Financial Assistance')
                            >
                                Financial Assistance
                            </option>

                            <option
                                value="Burial Assistance"
                                @selected(old('assistance_type') === 'Burial Assistance')
                            >
                                Burial Assistance
                            </option>

                            <option
                                value="Medical Assistance"
                                @selected(old('assistance_type') === 'Medical Assistance')
                            >
                                Medical Assistance
                            </option>

                        </select>

                        @error('assistance_type')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ADDRESSED TO --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-xl border border-gray-200
                           bg-white shadow-sm"
                >

                    <div class="border-b border-gray-200 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-lg bg-purple-50 text-purple-600"
                            >
                                <i class="fa-solid fa-building-columns"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Addressed To
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Select the agency or agencies the request
                                    will be addressed to.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid gap-4 px-6 py-6 sm:grid-cols-2">

                        {{-- DSWD --}}
                        <label class="cursor-pointer">

                            <input
                                type="checkbox"
                                name="addressed_to[]"
                                value="DSWD"
                                class="peer sr-only"
                                @checked(in_array('DSWD', old('addressed_to', [])))
                            >

                            <div
                                class="flex items-center gap-4 rounded-xl
                                       border border-gray-200 p-4 transition
                                       peer-checked:border-purple-500
                                       peer-checked:bg-purple-50
                                       peer-checked:ring-2
                                       peer-checked:ring-purple-100
                                       hover:border-purple-300"
                            >

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-gray-100 text-gray-600
                                           peer-checked:bg-purple-600
                                           peer-checked:text-white"
                                >
                                    <i class="fa-solid fa-building"></i>
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-gray-900">
                                        DSWD
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        Department of Social Welfare and Development
                                    </p>

                                </div>

                            </div>

                        </label>


                        {{-- DOH --}}
                        <label class="cursor-pointer">

                            <input
                                type="checkbox"
                                name="addressed_to[]"
                                value="DOH"
                                class="peer sr-only"
                                @checked(in_array('DOH', old('addressed_to', [])))
                            >

                            <div
                                class="flex items-center gap-4 rounded-xl
                                       border border-gray-200 p-4 transition
                                       peer-checked:border-purple-500
                                       peer-checked:bg-purple-50
                                       peer-checked:ring-2
                                       peer-checked:ring-purple-100
                                       hover:border-purple-300"
                            >

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-gray-100 text-gray-600"
                                >
                                    <i class="fa-solid fa-hospital"></i>
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-gray-900">
                                        DOH
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        Department of Health
                                    </p>

                                </div>

                            </div>

                        </label>

                    </div>


                    @error('addressed_to')
                        <div class="px-6 pb-6">

                            <p class="text-sm text-red-600">
                                {{ $message }}
                            </p>

                        </div>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- ADDITIONAL DETAILS --}}
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
                                <i class="fa-solid fa-clipboard"></i>
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-900">
                                    Additional Information
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Provide additional details about your request.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="px-6 py-6">

                        <label
                            for="remarks"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Remarks / Explanation
                            <span class="font-normal text-gray-400">
                                (Optional)
                            </span>
                        </label>

                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="4"
                            placeholder="Explain any additional information related to your assistance request..."
                            class="mt-1 block w-full rounded-lg border-gray-300
                                   shadow-sm focus:border-indigo-500
                                   focus:ring-indigo-500"
                        >{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- NOTICE --}}
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
                                Important
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-blue-700">
                                Please provide accurate and complete information.
                                Your request will be reviewed by an authorized
                                barangay official before the certificate is prepared
                                or released.
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