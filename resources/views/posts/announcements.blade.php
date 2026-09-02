<x-app-layout>

    {{-- ================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ================================================= --}}

    <section class="bg-white">

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <div class="mb-5">

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium
                           text-gray-500 transition hover:text-indigo-600"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Home
                </a>

            </div>


            {{-- Header --}}
            <div class="max-w-3xl">

                <div class="flex items-center gap-2">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-lg bg-indigo-50 text-indigo-600"
                    >
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>

                    <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">
                        Stay Updated
                    </p>

                </div>


                <h1
                    class="mt-4 text-3xl font-bold tracking-tight
                           text-gray-900 sm:text-4xl"
                >
                    Barangay Announcements
                </h1>


                <p class="mt-3 text-sm leading-6 text-gray-500 sm:text-base">
                    Stay informed about important notices, programs,
                    activities, and updates from Barangay San Lorenzo Ruiz.
                </p>

            </div>

        </div>

    </section>


    {{-- ================================================= --}}
    {{-- ANNOUNCEMENT LIST --}}
    {{-- ================================================= --}}

    <section class="bg-gray-50 py-10">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($announcements->count())

                {{-- Result count --}}
                <div class="mb-6 flex items-center justify-between">

                    <p class="text-sm text-gray-500">
                        Showing
                        <span class="font-semibold text-gray-700">
                            {{ $announcements->firstItem() }}
                        </span>
                        –
                        <span class="font-semibold text-gray-700">
                            {{ $announcements->lastItem() }}
                        </span>
                        of
                        <span class="font-semibold text-gray-700">
                            {{ $announcements->total() }}
                        </span>
                        announcements
                    </p>

                </div>


                {{-- Cards --}}
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @foreach($announcements as $announcement)

                        <article
                            class="group overflow-hidden rounded-2xl
                                   border border-gray-200 bg-white
                                   shadow-sm transition duration-200
                                   hover:-translate-y-1 hover:shadow-lg"
                        >

                            {{-- ================================================= --}}
                            {{-- IMAGE --}}
                            {{-- ================================================= --}}

                            <div class="h-48 overflow-hidden bg-gray-100">

                                @if($announcement->image)

                                    <img
                                        src="{{ Storage::url($announcement->image) }}"
                                        alt="{{ $announcement->title }}"
                                        class="h-full w-full object-cover
                                               transition duration-300
                                               group-hover:scale-105"
                                    >

                                @else

                                    <div
                                        class="flex h-full items-center
                                               justify-center bg-indigo-50
                                               text-indigo-400"
                                    >
                                        <i class="fa-solid fa-bullhorn text-4xl"></i>
                                    </div>

                                @endif

                            </div>


                            {{-- ================================================= --}}
                            {{-- CONTENT --}}
                            {{-- ================================================= --}}

                            <div class="p-5">

                                {{-- Badges --}}
                                <div class="flex flex-wrap items-center gap-2">

                                    @if($announcement->is_pinned)

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-red-50
                                                   px-2.5 py-1 text-xs
                                                   font-semibold text-red-600"
                                        >
                                            <i class="fa-solid fa-thumbtack"></i>
                                            Important
                                        </span>

                                    @endif


                                    <span
                                        class="rounded-full bg-indigo-50
                                               px-2.5 py-1 text-xs
                                               font-semibold text-indigo-600"
                                    >
                                        Announcement
                                    </span>

                                </div>


                                {{-- Date --}}
                                @if($announcement->published_at)

                                    <div
                                        class="mt-3 flex items-center gap-2
                                               text-xs text-gray-400"
                                    >

                                        <i class="fa-regular fa-calendar"></i>

                                        {{ $announcement->published_at->format('F d, Y') }}

                                    </div>

                                @endif


                                {{-- Title --}}
                                <h2
                                    class="mt-3 text-lg font-bold leading-7
                                           text-gray-900 transition
                                           group-hover:text-indigo-600"
                                >
                                    {{ $announcement->title }}
                                </h2>


                                {{-- Excerpt --}}
                                <p
                                    class="mt-2 line-clamp-3 text-sm
                                           leading-6 text-gray-500"
                                >
                                    {{ $announcement->excerpt ?: Str::limit(strip_tags($announcement->content), 160) }}
                                </p>


                                {{-- Read More --}}
                                <a
                                    href="{{ route('posts.show', $announcement->slug) }}"
                                    class="mt-5 inline-flex items-center gap-2
                                           text-sm font-semibold
                                           text-indigo-600
                                           transition hover:text-indigo-800"
                                >
                                    Read More
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>

                            </div>

                        </article>

                    @endforeach

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION --}}
                {{-- ================================================= --}}

                <div class="mt-8">

                    {{ $announcements->links() }}

                </div>

            @else

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-2xl border border-dashed border-gray-300
                           bg-white px-6 py-16 text-center"
                >

                    <div
                        class="mx-auto flex h-16 w-16 items-center
                               justify-center rounded-full bg-gray-100
                               text-gray-400"
                    >
                        <i class="fa-solid fa-bullhorn text-2xl"></i>
                    </div>


                    <h2 class="mt-5 text-lg font-bold text-gray-900">
                        No announcements available
                    </h2>


                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        There are currently no published announcements
                        from the barangay.
                    </p>


                    <a
                        href="{{ route('home') }}"
                        class="mt-6 inline-flex items-center gap-2
                               rounded-lg bg-indigo-600 px-4 py-2.5
                               text-sm font-semibold text-white
                               shadow-sm transition hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-house"></i>
                        Back to Home
                    </a>

                </div>

            @endif

        </div>

    </section>


    {{-- ================================================= --}}
    {{-- FOOTER --}}
    {{-- ================================================= --}}

    <footer class="border-t border-gray-200 bg-white">

        <div
            class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6
                   text-center sm:flex-row sm:items-center
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

</x-app-layout>