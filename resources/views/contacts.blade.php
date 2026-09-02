<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">
                Contact Us
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Get in touch with the Barangay San Lorenzo Ruiz office.
            </p>

        </div>

    </x-slot>


    <div class="bg-gray-50">

        {{-- ================================================= --}}
        {{-- HERO --}}
        {{-- ================================================= --}}

        <section class="bg-white">

            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

                <div class="relative overflow-hidden rounded-2xl bg-indigo-700 px-6 py-12 shadow-lg sm:px-10">

                    {{-- Decorative shapes --}}
                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-600"></div>
                    <div class="absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-indigo-800"></div>

                    <div class="relative max-w-3xl">

                        <span
                            class="inline-flex items-center gap-2 rounded-full
                                   bg-white/10 px-3 py-1.5
                                   text-xs font-semibold uppercase
                                   tracking-wider text-indigo-100
                                   ring-1 ring-inset ring-white/20"
                        >
                            <i class="fa-solid fa-comments"></i>
                            We are here to help
                        </span>

                        <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Contact Barangay San Lorenzo Ruiz
                        </h1>

                        <p class="mt-4 max-w-2xl text-sm leading-6 text-indigo-100 sm:text-base">
                            Have a question, concern, or need assistance with a
                            barangay service? Contact the barangay office through
                            the information below.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        {{-- ================================================= --}}
        {{-- CONTACT INFORMATION --}}
        {{-- ================================================= --}}

        <section class="py-10">

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">


                    {{-- Office --}}
                    <div
                        class="rounded-xl border border-gray-200
                               bg-white p-6 shadow-sm
                               transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-indigo-50 text-indigo-600"
                        >
                            <i class="fa-solid fa-building"></i>
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            Barangay Office
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Barangay Hall, Barangay San Lorenzo Ruiz,
                            City of San Pedro, Laguna
                        </p>

                    </div>


                    {{-- Phone --}}
                    <div
                        class="rounded-xl border border-gray-200
                               bg-white p-6 shadow-sm
                               transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-emerald-50 text-emerald-600"
                        >
                            <i class="fa-solid fa-phone"></i>
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            Phone
                        </h3>

                        <a
                            href="tel:09123456789"
                            class="mt-2 block text-sm font-medium text-indigo-600 hover:text-indigo-800"
                        >
                            (0912) 345-6789
                        </a>

                        <p class="mt-1 text-xs text-gray-500">
                            Barangay Hotline
                        </p>

                    </div>


                    {{-- Email --}}
                    <div
                        class="rounded-xl border border-gray-200
                               bg-white p-6 shadow-sm
                               transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-blue-50 text-blue-600"
                        >
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            Email
                        </h3>

                        <a
                            href="mailto:barangay@example.com"
                            class="mt-2 block break-all text-sm font-medium text-indigo-600 hover:text-indigo-800"
                        >
                            barangay@example.com
                        </a>

                        <p class="mt-1 text-xs text-gray-500">
                            General inquiries
                        </p>

                    </div>


                    {{-- Office Hours --}}
                    <div
                        class="rounded-xl border border-gray-200
                               bg-white p-6 shadow-sm
                               transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-lg bg-amber-50 text-amber-600"
                        >
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <h3 class="mt-4 font-bold text-gray-900">
                            Office Hours
                        </h3>

                        <p class="mt-2 text-sm font-medium text-gray-800">
                            Monday – Friday
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            8:00 AM – 5:00 PM
                        </p>

                    </div>

                </div>

            </div>

        </section>


        {{-- ================================================= --}}
        {{-- MAIN CONTACT AREA --}}
        {{-- ================================================= --}}

        <section class="pb-12">

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">


                    {{-- ================================================= --}}
                    {{-- CONTACT FORM --}}
                    {{-- ================================================= --}}

                    <div
                        class="lg:col-span-2
                               overflow-hidden rounded-xl
                               border border-gray-200
                               bg-white shadow-sm"
                    >

                        <div class="border-b border-gray-100 px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-indigo-50 text-indigo-600"
                                >
                                    <i class="fa-solid fa-paper-plane"></i>
                                </div>

                                <div>

                                    <h3 class="font-bold text-gray-900">
                                        Send Us a Message
                                    </h3>

                                    <p class="mt-0.5 text-sm text-gray-500">
                                        Submit your concern and we'll get back to you.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <form
                            method="POST"
                            action="#"
                            class="space-y-5 p-6"
                        >

                            @csrf


                            {{-- Name + Email --}}
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <div>

                                    <x-input-label
                                        for="name"
                                        value="Full Name"
                                    />

                                    <x-text-input
                                        id="name"
                                        name="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Enter your full name"
                                        required
                                    />

                                </div>


                                <div>

                                    <x-input-label
                                        for="email"
                                        value="Email Address"
                                    />

                                    <x-text-input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        placeholder="you@example.com"
                                        required
                                    />

                                </div>

                            </div>


                            {{-- Contact Number --}}
                            <div>

                                <x-input-label
                                    for="contact_number"
                                    value="Contact Number"
                                />

                                <x-text-input
                                    id="contact_number"
                                    name="contact_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g. 0912 345 6789"
                                />

                            </div>


                            {{-- Subject --}}
                            <div>

                                <x-input-label
                                    for="subject"
                                    value="Subject"
                                />

                                <select
                                    id="subject"
                                    name="subject"
                                    class="mt-1 block w-full rounded-lg
                                           border-gray-300
                                           shadow-sm
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                    required
                                >

                                    <option value="">
                                        Select a concern
                                    </option>

                                    <option value="General Inquiry">
                                        General Inquiry
                                    </option>

                                    <option value="Certificate Request">
                                        Certificate Request
                                    </option>

                                    <option value="Blotter Concern">
                                        Blotter Concern
                                    </option>

                                    <option value="Immunization">
                                        Immunization Concern
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>


                            {{-- Message --}}
                            <div>

                                <x-input-label
                                    for="message"
                                    value="Message"
                                />

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="6"
                                    required
                                    placeholder="Describe your concern..."
                                    class="mt-1 block w-full rounded-lg
                                           border-gray-300
                                           shadow-sm
                                           placeholder:text-gray-400
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                ></textarea>

                            </div>


                            {{-- Submit --}}
                            <div class="flex justify-end border-t border-gray-100 pt-5">

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
                                           transition
                                           hover:bg-indigo-700
                                           hover:shadow-md
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-indigo-500
                                           focus:ring-offset-2"
                                >
                                    <i class="fa-solid fa-paper-plane"></i>
                                    Send Message
                                </button>

                            </div>

                        </form>

                    </div>


                    {{-- ================================================= --}}
                    {{-- OFFICE DETAILS --}}
                    {{-- ================================================= --}}

                    <div class="space-y-5">


                        {{-- Punong Barangay --}}
                        <div
                            class="rounded-xl border border-gray-200
                                   bg-white p-6 shadow-sm"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-indigo-50 text-indigo-600"
                                >
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>

                                <div>

                                    <p class="text-xs font-semibold uppercase
                                              tracking-wider text-gray-400">
                                        Punong Barangay
                                    </p>

                                    <h3 class="mt-0.5 font-bold text-gray-900">
                                        Hon. Romeo B. Bonoan
                                    </h3>

                                </div>

                            </div>

                            <div class="mt-5 space-y-3 text-sm">

                                <p class="flex items-start gap-3 text-gray-600">
                                    <i class="fa-solid fa-phone mt-0.5 w-4 text-center text-gray-400"></i>
                                    <span>(0912) 123-1231</span>
                                </p>

                                <p class="flex items-start gap-3 text-gray-600">
                                    <i class="fa-solid fa-envelope mt-0.5 w-4 text-center text-gray-400"></i>
                                    <span>punongbarangay@example.com</span>
                                </p>

                            </div>

                        </div>


                        {{-- Office Hours --}}
                        <div
                            class="rounded-xl border border-gray-200
                                   bg-white p-6 shadow-sm"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-emerald-50 text-emerald-600"
                                >
                                    <i class="fa-solid fa-calendar-days"></i>
                                </div>

                                <h3 class="font-bold text-gray-900">
                                    Office Hours
                                </h3>

                            </div>


                            <div class="mt-4 divide-y divide-gray-100 text-sm">

                                <div class="flex justify-between py-2">
                                    <span class="text-gray-500">
                                        Monday – Friday
                                    </span>

                                    <span class="font-medium text-gray-800">
                                        8:00 AM – 5:00 PM
                                    </span>
                                </div>

                                <div class="flex justify-between py-2">
                                    <span class="text-gray-500">
                                        Saturday
                                    </span>

                                    <span class="font-medium text-gray-800">
                                        8:00 AM – 12:00 PM
                                    </span>
                                </div>

                                <div class="flex justify-between py-2">
                                    <span class="text-gray-500">
                                        Sunday
                                    </span>

                                    <span class="font-medium text-gray-800">
                                        Closed
                                    </span>
                                </div>

                            </div>

                        </div>


                        {{-- Location --}}
                        <div
                            class="rounded-xl border border-gray-200
                                   bg-white p-6 shadow-sm"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center
                                           rounded-lg bg-red-50 text-red-600"
                                >
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>

                                <h3 class="font-bold text-gray-900">
                                    Visit Us
                                </h3>

                            </div>

                            <p class="mt-4 text-sm leading-6 text-gray-600">
                                Barangay Hall<br>
                                Barangay San Lorenzo Ruiz<br>
                                City of San Pedro, Laguna
                            </p>

                            <a
                                href="https://maps.app.goo.gl/DR7Jtn8LaV8LgR6CA"
                                target="_blank"
                                class="mt-4 inline-flex items-center gap-2
                                       text-sm font-semibold text-indigo-600
                                       hover:text-indigo-800"
                            >
                                <i class="fa-solid fa-map-location-dot"></i>
                                View on Map
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ================================================= --}}
        {{-- EMERGENCY --}}
        {{-- ================================================= --}}

        <section class="pb-12">

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div
                    class="rounded-xl
                           border border-red-200
                           bg-red-50
                           px-6 py-6"
                >

                    <div
                        class="flex flex-col gap-5
                               sm:flex-row sm:items-center
                               sm:justify-between"
                    >

                        <div class="flex items-start gap-4">

                            <div
                                class="flex h-11 w-11 shrink-0
                                       items-center justify-center
                                       rounded-full
                                       bg-red-100
                                       text-red-600"
                            >
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>

                            <div>

                                <h3 class="font-bold text-red-900">
                                    Emergency?
                                </h3>

                                <p class="mt-1 text-sm text-red-700">
                                    For emergencies, contact the appropriate
                                    emergency service immediately.
                                </p>

                            </div>

                        </div>


                        <div class="flex flex-wrap gap-3">

                            <a
                                href="tel:911"
                                class="inline-flex items-center gap-2
                                       rounded-lg bg-red-600
                                       px-4 py-2.5
                                       text-sm font-semibold text-white
                                       shadow-sm hover:bg-red-700"
                            >
                                <i class="fa-solid fa-phone"></i>
                                Call 911
                            </a>


                            <a
                                href="tel:09123456789"
                                class="inline-flex items-center gap-2
                                       rounded-lg border border-red-200
                                       bg-white
                                       px-4 py-2.5
                                       text-sm font-semibold text-red-700
                                       hover:bg-red-100"
                            >
                                <i class="fa-solid fa-building"></i>
                                Barangay Hotline
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ================================================= --}}
        {{-- FOOTER --}}
        {{-- ================================================= --}}

        <footer class="border-t border-gray-200 bg-white">

            <div
                class="mx-auto flex max-w-7xl flex-col gap-2
                       px-4 py-6 text-center
                       sm:flex-row sm:items-center
                       sm:justify-between sm:px-6 lg:px-8"
            >

                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} Barangay San Lorenzo Ruiz.
                    All rights reserved.
                </p>

                <p class="text-xs text-gray-400">
                    BrgyConnect Barangay Information System
                </p>

            </div>

        </footer>

    </div>

</x-app-layout>