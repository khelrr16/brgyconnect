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
                        @auth
                            Welcome, {{ auth()->user()->name }}!
                        @else
                            Welcome to BrgyConnect!
                        @endauth
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">
                        Stay informed with the latest announcements, news,
                        events, and services from your barangay.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">

                        <a
                            href="#announcements"
                            class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5
                                   text-sm font-semibold text-indigo-700 shadow-sm hover:bg-indigo-50"
                        >
                            <i class="fa-solid fa-bullhorn"></i>
                            View Announcements
                        </a>

                        <a
                            href="#services"
                            class="inline-flex items-center gap-2 rounded-lg border border-indigo-400
                                   px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-600"
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
    {{-- ANNOUNCEMENTS --}}
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

                @if($announcements->count() > 0)
                    <a
                        href="{{ route('announcements.index') }}"
                        class="hidden text-sm font-semibold text-indigo-600 hover:text-indigo-800 sm:block"
                    >
                        View All
                        <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                @endif

            </div>


            @if($announcements->count())

                <div class="grid gap-5 md:grid-cols-3">

                    @foreach($announcements as $announcement)

                        <article
                            class="group overflow-hidden rounded-xl border border-gray-200
                                   bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >

                            {{-- Image --}}
                            <div class="h-40 overflow-hidden bg-gray-100">

                                @if($announcement->image)

                                    <img
                                        src="{{ Storage::url($announcement->image) }}"
                                        alt="{{ $announcement->title }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    >

                                @else

                                    <div class="flex h-full items-center justify-center bg-indigo-50 text-indigo-400">
                                        <i class="fa-solid fa-bullhorn text-4xl"></i>
                                    </div>

                                @endif

                            </div>


                            {{-- Content --}}
                            <div class="p-5">

                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">

                                    @if($announcement->is_pinned)

                                        <span
                                            class="rounded-full bg-red-50 px-2.5 py-1 font-semibold text-red-600"
                                        >
                                            <i class="fa-solid fa-thumbtack mr-1"></i>
                                            Important
                                        </span>

                                    @else

                                        <span
                                            class="rounded-full bg-indigo-50 px-2.5 py-1 font-semibold text-indigo-600"
                                        >
                                            Announcement
                                        </span>

                                    @endif

                                    @if($announcement->published_at)

                                        <span>
                                            {{ $announcement->published_at->format('F d, Y') }}
                                        </span>

                                    @endif

                                </div>


                                <h3 class="mt-3 text-lg font-bold text-gray-900">
                                    {{ $announcement->title }}
                                </h3>

                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-500">
                                    {{ $announcement->excerpt ?: Str::limit(strip_tags($announcement->content), 150) }}
                                </p>

                                <a
                                    href="{{ route('posts.show', $announcement->slug) }}"
                                    class="mt-4 inline-flex items-center gap-1.5 text-sm
                                           font-semibold text-indigo-600 hover:text-indigo-800"
                                >
                                    Read More
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <i class="fa-solid fa-bullhorn text-xl"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-gray-900">
                        No announcements available
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        There are currently no published announcements.
                    </p>

                </div>

            @endif

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


                    @if($news->count())

                        <div class="space-y-4">

                            @foreach($news as $post)

                                <article
                                    class="flex gap-4 rounded-xl border border-gray-200
                                           bg-white p-4 shadow-sm transition hover:shadow-md"
                                >

                                    {{-- Image --}}
                                    <div class="hidden h-28 w-40 shrink-0 overflow-hidden rounded-lg bg-gray-100 sm:block">

                                        @if($post->image)

                                            <img
                                                src="{{ Storage::url($post->image) }}"
                                                alt="{{ $post->title }}"
                                                class="h-full w-full object-cover"
                                            >

                                        @else

                                            <div class="flex h-full w-full items-center justify-center bg-indigo-50 text-indigo-400">
                                                <i class="fa-solid fa-newspaper text-3xl"></i>
                                            </div>

                                        @endif

                                    </div>


                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">

                                            <span class="font-semibold text-indigo-600">
                                                Barangay News
                                            </span>

                                            @if($post->published_at)

                                                <span>•</span>

                                                <span>
                                                    {{ $post->published_at->format('F d, Y') }}
                                                </span>

                                            @endif

                                        </div>


                                        <h3 class="mt-2 text-lg font-bold text-gray-900">
                                            {{ $post->title }}
                                        </h3>


                                        <p class="mt-1 text-sm leading-6 text-gray-500">
                                            {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 180) }}
                                        </p>


                                        <a
                                            href="#"
                                            class="mt-2 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                                        >
                                            Read Article
                                            <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                                        </a>

                                    </div>

                                </article>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">

                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-gray-400 shadow-sm">
                                <i class="fa-solid fa-newspaper text-xl"></i>
                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-gray-900">
                                No news available
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                There are currently no published news articles.
                            </p>

                        </div>

                    @endif

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


                    @if($events->isNotEmpty())

                        <div class="space-y-3">

                            @foreach($events as $event)

                                <a
                                    href="{{ route('posts.show', $event->slug) }}"
                                    class="group flex gap-4 rounded-xl border border-gray-200
                                        bg-white p-4 shadow-sm transition hover:-translate-y-0.5
                                        hover:shadow-md"
                                >

                                    {{-- Date --}}
                                    <div
                                        class="flex h-14 w-14 shrink-0 flex-col items-center
                                            justify-center rounded-lg bg-indigo-50
                                            text-indigo-700"
                                    >

                                        <span class="text-xs font-semibold uppercase">
                                            {{ $event->event_date->format('M') }}
                                        </span>

                                        <span class="text-xl font-bold leading-none">
                                            {{ $event->event_date->format('d') }}
                                        </span>

                                    </div>


                                    {{-- Event Info --}}
                                    <div class="min-w-0">

                                        <h3
                                            class="font-semibold text-gray-900
                                                transition group-hover:text-indigo-600"
                                        >
                                            {{ $event->title }}
                                        </h3>


                                        @if($event->event_time)

                                            <p class="mt-1 text-xs text-gray-500">

                                                <i class="fa-solid fa-clock mr-1"></i>

                                                {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}

                                            </p>

                                        @endif


                                        @if($event->event_location)

                                            <p class="mt-1 text-xs text-gray-500">

                                                <i class="fa-solid fa-location-dot mr-1"></i>

                                                {{ $event->event_location }}

                                            </p>

                                        @endif


                                        @if(
                                            $event->event_end_date &&
                                            $event->event_end_date->ne($event->event_date)
                                        )

                                            <p class="mt-1 text-xs text-gray-400">

                                                <i class="fa-solid fa-calendar-days mr-1"></i>

                                                Until {{ $event->event_end_date->format('M d, Y') }}

                                            </p>

                                        @endif

                                    </div>

                                </a>

                            @endforeach

                        </div>

                    @else

                        <div
                            class="rounded-xl border border-dashed border-gray-300
                                bg-gray-50 px-6 py-10 text-center"
                        >

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center
                                    rounded-full bg-white text-gray-400 shadow-sm"
                            >
                                <i class="fa-solid fa-calendar-days text-xl"></i>
                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-gray-900">
                                No upcoming events
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Upcoming barangay events will appear here.
                            </p>

                        </div>

                    @endif

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


                {{-- ================================================= --}}
                {{-- CERTIFICATES --}}
                {{-- ================================================= --}}

                <a
                    href="#"
                    class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-lg
                               bg-indigo-50 text-indigo-600 transition
                               group-hover:bg-indigo-600 group-hover:text-white"
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


                {{-- ================================================= --}}
                {{-- BLOTTER --}}
                {{-- ================================================= --}}

                <a
                    href="#"
                    class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-lg
                               bg-red-50 text-red-600 transition
                               group-hover:bg-red-600 group-hover:text-white"
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


                {{-- ================================================= --}}
                {{-- IMMUNIZATION --}}
                {{-- ================================================= --}}

                <a
                    href="#"
                    class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-lg
                               bg-emerald-50 text-emerald-600 transition
                               group-hover:bg-emerald-600 group-hover:text-white"
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


                {{-- ================================================= --}}
                {{-- PROFILE --}}
                {{-- ================================================= --}}

                <a
                    href="#"
                    class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm
                           transition hover:-translate-y-1 hover:shadow-md"
                >

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-lg
                               bg-amber-50 text-amber-600 transition
                               group-hover:bg-amber-600 group-hover:text-white"
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
                class="flex flex-col gap-5 rounded-xl border border-red-200
                       bg-red-50 p-6 sm:flex-row sm:items-center sm:justify-between"
            >

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center
                               rounded-full bg-red-100 text-red-600"
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
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600
                               px-4 py-2.5 text-sm font-semibold text-white
                               shadow-sm hover:bg-red-700"
                    >
                        <i class="fa-solid fa-phone"></i>
                        911
                    </a>

                    <a
                        href="tel:09123456789"
                        class="inline-flex items-center gap-2 rounded-lg border border-red-200
                               bg-white px-4 py-2.5 text-sm font-semibold text-red-700
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
            class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6
                   text-center sm:flex-row sm:items-center sm:justify-between
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