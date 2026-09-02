<x-app-layout>

    {{-- ================================================= --}}
    {{-- HERO / WELCOME --}}
    {{-- ================================================= --}}

    <section class="bg-white">

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="relative overflow-hidden rounded-2xl bg-indigo-700 px-6 py-10 shadow-lg sm:px-10">

                {{-- Decorative background --}}
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-600 opacity-50"></div>
                <div class="absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-indigo-800 opacity-40"></div>

                <div class="relative max-w-2xl">

                    <p class="mb-2 text-sm font-medium uppercase tracking-wider text-indigo-200">
                        Barangay San Lorenzo Ruiz
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        @auth Welcome, {{ auth()->user()->name }}! @endauth
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">
                        Stay informed with the latest announcements, news,
                        events, and services from your barangay.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">

                        <a
                            href="#announcements"
                            class="inline-flex items-center gap-2 rounded-lg
                                   bg-white px-4 py-2.5
                                   text-sm font-semibold text-indigo-700
                                   shadow-sm hover:bg-indigo-50"
                        >
                            <i class="fa-solid fa-bullhorn"></i>
                            View Announcements
                        </a>

                        <a
                            href="#services"
                            class="inline-flex items-center gap-2 rounded-lg
                                   border border-indigo-400
                                   px-4 py-2.5
                                   text-sm font-semibold text-white
                                   hover:bg-indigo-600"
                        >
                            <i class="fa-solid fa-grid-2"></i>
                            Barangay Services
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ================================================= --}}
    {{-- IMPORTANT ANNOUNCEMENT --}}
    {{-- ================================================= --}}

    <section
        id="announcements"
        class="bg-gray-50 py-10"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-6 flex items-end justify-between gap-4">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                        Stay Updated
                    </p>

                    <h2 class="mt-1 text-2xl font-bold text-gray-900">
                        Announcements
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Important information from the barangay.
                    </p>

                </div>

                <a
                    href="#"
                    class="hidden text-sm font-semibold text-indigo-600 hover:text-indigo-800 sm:block"
                >
                    View All
                    <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>

            </div>


            {{-- Announcement Cards --}}
            <div class="grid gap-5 md:grid-cols-3">


                {{-- Announcement 1 --}}
                <article
                    class="group overflow-hidden rounded-xl
                           border border-gray-200
                           bg-white shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div class="h-40 overflow-hidden bg-gray-100">

                        <img
                            src="{{ asset('images/cms/announcement-1.webp') }}"
                            alt="Barangay announcement"
                            class="h-full w-full object-cover
                                   transition duration-300
                                   group-hover:scale-105"
                        >

                    </div>

                    <div class="p-5">

                        <div class="flex items-center gap-2 text-xs text-gray-500">

                            <span
                                class="rounded-full bg-red-50 px-2.5 py-1
                                       font-semibold text-red-600"
                            >
                                Important
                            </span>

                            <span>
                                August 28, 2026
                            </span>

                        </div>

                        <h3 class="mt-3 text-lg font-bold text-gray-900">
                            Barangay Assembly Meeting
                        </h3>

                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-500">
                            All residents are invited to attend the upcoming
                            Barangay Assembly Meeting to discuss community
                            programs, projects, and other important matters.
                        </p>

                        <a
                            href="#"
                            class="mt-4 inline-flex items-center gap-1.5
                                   text-sm font-semibold text-indigo-600
                                   hover:text-indigo-800"
                        >
                            Read More
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                    </div>

                </article>


                {{-- Announcement 2 --}}
                <article
                    class="group overflow-hidden rounded-xl
                           border border-gray-200
                           bg-white shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div class="h-40 overflow-hidden bg-gray-100">

                        <img
                            src="{{ asset('images/cms/announcement-2.png') }}"
                            alt="Community announcement"
                            class="h-full w-full object-cover
                                   transition duration-300
                                   group-hover:scale-105"
                        >

                    </div>

                    <div class="p-5">

                        <div class="flex items-center gap-2 text-xs text-gray-500">

                            <span
                                class="rounded-full bg-blue-50 px-2.5 py-1
                                       font-semibold text-blue-600"
                            >
                                Community
                            </span>

                            <span>
                                August 25, 2026
                            </span>

                        </div>

                        <h3 class="mt-3 text-lg font-bold text-gray-900">
                            Community Clean-Up Drive
                        </h3>

                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-500">
                            Residents are encouraged to participate in the
                            upcoming community-wide clean-up drive.
                        </p>

                        <a
                            href="#"
                            class="mt-4 inline-flex items-center gap-1.5
                                   text-sm font-semibold text-indigo-600
                                   hover:text-indigo-800"
                        >
                            Read More
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                    </div>

                </article>


                {{-- Announcement 3 --}}
                <article
                    class="group overflow-hidden rounded-xl
                           border border-gray-200
                           bg-white shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div class="h-40 overflow-hidden bg-gray-100">

                        <img
                            src="{{ asset('images/cms/announcement-3.png') }}"
                            alt="Barangay program"
                            class="h-full w-full object-cover
                                   transition duration-300
                                   group-hover:scale-105"
                        >

                    </div>

                    <div class="p-5">

                        <div class="flex items-center gap-2 text-xs text-gray-500">

                            <span
                                class="rounded-full bg-emerald-50 px-2.5 py-1
                                       font-semibold text-emerald-600"
                            >
                                Program
                            </span>

                            <span>
                                August 22, 2026
                            </span>

                        </div>

                        <h3 class="mt-3 text-lg font-bold text-gray-900">
                            Free Health Check-Up
                        </h3>

                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-500">
                            Free medical consultations and basic health
                            screening will be available at the Barangay Health
                            Center.
                        </p>

                        <a
                            href="#"
                            class="mt-4 inline-flex items-center gap-1.5
                                   text-sm font-semibold text-indigo-600
                                   hover:text-indigo-800"
                        >
                            Read More
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                    </div>

                </article>

            </div>

        </div>

    </section>


    {{-- ================================================= --}}
    {{-- NEWS + EVENTS --}}
    {{-- ================================================= --}}

    <section class="bg-white py-10">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="grid gap-8 lg:grid-cols-3">


                {{-- ================================================= --}}
                {{-- NEWS --}}
                {{-- ================================================= --}}

                <div class="lg:col-span-2">

                    <div class="mb-5">

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Barangay Updates
                        </p>

                        <h2 class="mt-1 text-2xl font-bold text-gray-900">
                            Latest News
                        </h2>

                    </div>


                    <div class="space-y-4">


                        {{-- News Item --}}
                        <article
                            class="flex gap-4 rounded-xl border border-gray-200
                                   bg-white p-4 shadow-sm
                                   hover:shadow-md transition"
                        >

                            <div class="hidden h-28 w-40 shrink-0 overflow-hidden rounded-lg sm:block">

                                <img
                                    src="{{ asset('images/cms/news-1.jpg') }}"
                                    alt="Barangay news"
                                    class="h-full w-full object-cover"
                                >

                            </div>

                            <div class="min-w-0">

                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">

                                    <span class="font-semibold text-indigo-600">
                                        Barangay News
                                    </span>

                                    <span>•</span>

                                    <span>August 27, 2026</span>

                                </div>

                                <h3 class="mt-2 text-lg font-bold text-gray-900">
                                    Barangay Infrastructure Project Update
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    The barangay has completed another phase of
                                    the ongoing road improvement project...
                                </p>

                                <a
                                    href="#"
                                    class="mt-2 inline-block text-sm font-semibold text-indigo-600"
                                >
                                    Read Article
                                    <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                </a>

                            </div>

                        </article>


                        {{-- News Item --}}
                        <article
                            class="flex gap-4 rounded-xl border border-gray-200
                                   bg-white p-4 shadow-sm
                                   hover:shadow-md transition"
                        >

                            <div class="hidden h-28 w-40 shrink-0 overflow-hidden rounded-lg sm:block">

                                <img
                                    src="{{ asset('images/cms/news-2.jpg') }}"
                                    alt="Barangay community"
                                    class="h-full w-full object-cover"
                                >

                            </div>

                            <div class="min-w-0">

                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">

                                    <span class="font-semibold text-indigo-600">
                                        Community
                                    </span>

                                    <span>•</span>

                                    <span>August 20, 2026</span>

                                </div>

                                <h3 class="mt-2 text-lg font-bold text-gray-900">
                                    Youth Development Program Launched
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    A new youth development initiative has been
                                    launched to provide educational and
                                    recreational activities...
                                </p>

                                <a
                                    href="#"
                                    class="mt-2 inline-block text-sm font-semibold text-indigo-600"
                                >
                                    Read Article
                                    <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                </a>

                            </div>

                        </article>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- UPCOMING EVENTS --}}
                {{-- ================================================= --}}

                <div>

                    <div class="mb-5">

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            What's Coming Up
                        </p>

                        <h2 class="mt-1 text-2xl font-bold text-gray-900">
                            Upcoming Events
                        </h2>

                    </div>


                    <div class="space-y-3">


                        {{-- Event --}}
                        <div
                            class="flex gap-4 rounded-xl
                                   border border-gray-200
                                   bg-white p-4 shadow-sm"
                        >

                            <div class="flex h-14 w-14 shrink-0 flex-col
                                        items-center justify-center
                                        rounded-lg bg-indigo-50
                                        text-indigo-700">

                                <span class="text-xs font-semibold uppercase">
                                    Sep
                                </span>

                                <span class="text-xl font-bold leading-none">
                                    05
                                </span>

                            </div>

                            <div>

                                <h3 class="font-semibold text-gray-900">
                                    Barangay Assembly
                                </h3>

                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    9:00 AM
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                    Barangay Hall
                                </p>

                            </div>

                        </div>


                        {{-- Event --}}
                        <div
                            class="flex gap-4 rounded-xl
                                   border border-gray-200
                                   bg-white p-4 shadow-sm"
                        >

                            <div class="flex h-14 w-14 shrink-0 flex-col
                                        items-center justify-center
                                        rounded-lg bg-emerald-50
                                        text-emerald-700">

                                <span class="text-xs font-semibold uppercase">
                                    Sep
                                </span>

                                <span class="text-xl font-bold leading-none">
                                    12
                                </span>

                            </div>

                            <div>

                                <h3 class="font-semibold text-gray-900">
                                    Community Clean-Up
                                </h3>

                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    7:00 AM
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                    Barangay Grounds
                                </p>

                            </div>

                        </div>


                        {{-- Event --}}
                        <div
                            class="flex gap-4 rounded-xl
                                   border border-gray-200
                                   bg-white p-4 shadow-sm"
                        >

                            <div class="flex h-14 w-14 shrink-0 flex-col
                                        items-center justify-center
                                        rounded-lg bg-amber-50
                                        text-amber-700">

                                <span class="text-xs font-semibold uppercase">
                                    Sep
                                </span>

                                <span class="text-xl font-bold leading-none">
                                    20
                                </span>

                            </div>

                            <div>

                                <h3 class="font-semibold text-gray-900">
                                    Free Medical Check-Up
                                </h3>

                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    8:00 AM
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                    Health Center
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ================================================= --}}
    {{-- QUICK SERVICES --}}
    {{-- ================================================= --}}

    <section
        id="services"
        class="bg-gray-50 py-10"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-6">

                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                    Online Services
                </p>

                <h2 class="mt-1 text-2xl font-bold text-gray-900">
                    Barangay Services
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Access available barangay services online.
                </p>

            </div>


            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">


                {{-- Certificate --}}
                <a
                    href="#"
                    class="group rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-lg bg-indigo-50
                               text-indigo-600
                               group-hover:bg-indigo-600
                               group-hover:text-white
                               transition"
                    >
                        <i class="fa-solid fa-file-certificate text-lg"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-gray-900">
                        Certificates
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-gray-500">
                        Request barangay certificates.
                    </p>

                </a>


                {{-- Blotter --}}
                <a
                    href="#"
                    class="group rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-lg bg-red-50
                               text-red-600
                               group-hover:bg-red-600
                               group-hover:text-white
                               transition"
                    >
                        <i class="fa-solid fa-file-lines text-lg"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-gray-900">
                        Blotter
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-gray-500">
                        View your blotter records.
                    </p>

                </a>


                {{-- Immunization --}}
                <a
                    href="#"
                    class="group rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-lg bg-emerald-50
                               text-emerald-600
                               group-hover:bg-emerald-600
                               group-hover:text-white
                               transition"
                    >
                        <i class="fa-solid fa-syringe text-lg"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-gray-900">
                        Immunization
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-gray-500">
                        View immunization records.
                    </p>

                </a>


                {{-- Profile --}}
                <a
                    href="#"
                    class="group rounded-xl border border-gray-200
                           bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-lg bg-amber-50
                               text-amber-600
                               group-hover:bg-amber-600
                               group-hover:text-white
                               transition"
                    >
                        <i class="fa-solid fa-user text-lg"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-gray-900">
                        My Profile
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-gray-500">
                        View your resident information.
                    </p>

                </a>

            </div>

        </div>

    </section>


    {{-- ================================================= --}}
    {{-- EMERGENCY CONTACT --}}
    {{-- ================================================= --}}

    <section class="bg-white py-10">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div
                class="flex flex-col gap-5 rounded-xl
                       border border-red-200
                       bg-red-50 p-6
                       sm:flex-row sm:items-center
                       sm:justify-between"
            >

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center
                               justify-center rounded-full
                               bg-red-100 text-red-600"
                    >
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div>

                        <h3 class="font-bold text-red-900">
                            Emergency Contacts
                        </h3>

                        <p class="mt-1 text-sm text-red-700">
                            For emergencies, contact the appropriate
                            barangay office immediately.
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
                        911
                    </a>

                    <a
                        href="tel:09123456789"
                        class="inline-flex items-center gap-2
                               rounded-lg border border-red-200
                               bg-white
                               px-4 py-2.5
                               text-sm font-semibold text-red-700
                               hover:bg-red-50"
                    >
                        <i class="fa-solid fa-phone"></i>
                        Barangay Hotline
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- ================================================= --}}
    {{-- FOOTER --}}
    {{-- ================================================= --}}

    <footer class="border-t border-gray-200 bg-white">

        <div
            class="mx-auto flex max-w-7xl flex-col
                   gap-2 px-4 py-6
                   text-center sm:flex-row
                   sm:items-center sm:justify-between
                   sm:px-6 lg:px-8"
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

</x-app-layout>