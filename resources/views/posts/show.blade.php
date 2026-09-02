<x-app-layout>

    {{-- ================================================= --}}
    {{-- ARTICLE --}}
    {{-- ================================================= --}}

    <section class="bg-gray-50 py-10">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="grid gap-8 lg:grid-cols-3">


                {{-- ================================================= --}}
                {{-- MAIN ARTICLE --}}
                {{-- ================================================= --}}

                <article class="lg:col-span-2">

                    {{-- Back Button --}}
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


                    {{-- Article Card --}}
                    <div
                        class="overflow-hidden rounded-2xl border border-gray-200
                               bg-white shadow-sm"
                    >

                        {{-- ================================================= --}}
                        {{-- FEATURED IMAGE --}}
                        {{-- ================================================= --}}

                        @if($post->image)

                            <div class="h-64 overflow-hidden sm:h-80 lg:h-96">

                                <img
                                    src="{{ Storage::url($post->image) }}"
                                    alt="{{ $post->title }}"
                                    class="h-full w-full object-cover"
                                >

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- ARTICLE CONTENT --}}
                        {{-- ================================================= --}}

                        <div class="px-6 py-8 sm:px-10 sm:py-10">

                            {{-- Category / Date --}}
                            <div class="flex flex-wrap items-center gap-2 text-sm">

                                @if($post->type === 'announcement')

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full bg-indigo-50
                                               px-3 py-1 font-semibold text-indigo-700"
                                    >
                                        <i class="fa-solid fa-bullhorn"></i>
                                        Announcement
                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full bg-emerald-50
                                               px-3 py-1 font-semibold text-emerald-700"
                                    >
                                        <i class="fa-solid fa-newspaper"></i>
                                        Barangay News
                                    </span>

                                @endif


                                @if($post->is_pinned)

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full bg-red-50
                                               px-3 py-1 font-semibold text-red-600"
                                    >
                                        <i class="fa-solid fa-thumbtack"></i>
                                        Important
                                    </span>

                                @endif

                            </div>


                            {{-- Title --}}
                            <h1
                                class="mt-4 text-3xl font-bold tracking-tight
                                       text-gray-900 sm:text-4xl"
                            >
                                {{ $post->title }}
                            </h1>


                            {{-- Date / Author --}}
                            <div
                                class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2
                                       text-sm text-gray-500"
                            >

                                @if($post->published_at)

                                    <div class="flex items-center gap-2">

                                        <i class="fa-regular fa-calendar"></i>

                                        <span>
                                            {{ $post->published_at->format('F d, Y') }}
                                        </span>

                                    </div>

                                @endif


                                @if($post->author)

                                    <div class="flex items-center gap-2">

                                        <i class="fa-regular fa-user"></i>

                                        <span>
                                            Posted by {{ $post->author->name }}
                                        </span>

                                    </div>

                                @endif

                            </div>


                            {{-- Divider --}}
                            <div class="my-7 border-t border-gray-200"></div>


                            {{-- ================================================= --}}
                            {{-- EXCERPT --}}
                            {{-- ================================================= --}}

                            @if($post->excerpt)

                                <p
                                    class="mb-7 text-lg font-medium leading-8
                                           text-gray-700"
                                >
                                    {{ $post->excerpt }}
                                </p>

                            @endif


                            {{-- ================================================= --}}
                            {{-- EVENT INFORMATION --}}
                            {{-- ================================================= --}}

                            @if($post->is_event)

                                <div
                                    class="mb-8 overflow-hidden rounded-xl
                                           border border-indigo-200 bg-indigo-50"
                                >

                                    <div
                                        class="flex items-center gap-3 border-b
                                               border-indigo-100 px-5 py-4"
                                    >

                                        <div
                                            class="flex h-10 w-10 items-center
                                                   justify-center rounded-lg
                                                   bg-indigo-600 text-white"
                                        >
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </div>

                                        <div>

                                            <p
                                                class="text-xs font-semibold
                                                       uppercase tracking-wider
                                                       text-indigo-600"
                                            >
                                                Upcoming Event
                                            </p>

                                            <h2 class="font-bold text-gray-900">
                                                {{ $post->title }}
                                            </h2>

                                        </div>

                                    </div>


                                    <div class="grid gap-4 px-5 py-5 sm:grid-cols-2">

                                        {{-- Date --}}
                                        @if($post->event_date)

                                            <div class="flex items-start gap-3">

                                                <div class="mt-0.5 text-indigo-600">
                                                    <i class="fa-solid fa-calendar"></i>
                                                </div>

                                                <div>

                                                    <p class="text-xs font-medium text-gray-500">
                                                        Date
                                                    </p>

                                                    @if(
                                                        $post->event_end_date &&
                                                        $post->event_end_date->ne($post->event_date)
                                                    )

                                                        <p class="mt-0.5 text-sm font-semibold text-gray-900">
                                                            {{ $post->event_date->format('F d, Y') }}
                                                            –
                                                            {{ $post->event_end_date->format('F d, Y') }}
                                                        </p>

                                                    @else

                                                        <p class="mt-0.5 text-sm font-semibold text-gray-900">
                                                            {{ $post->event_date->format('F d, Y') }}
                                                        </p>

                                                    @endif

                                                </div>

                                            </div>

                                        @endif


                                        {{-- Time --}}
                                        @if($post->event_time)

                                            <div class="flex items-start gap-3">

                                                <div class="mt-0.5 text-indigo-600">
                                                    <i class="fa-solid fa-clock"></i>
                                                </div>

                                                <div>

                                                    <p class="text-xs font-medium text-gray-500">
                                                        Time
                                                    </p>

                                                    <p class="mt-0.5 text-sm font-semibold text-gray-900">
                                                        {{ \Carbon\Carbon::parse($post->event_time)->format('g:i A') }}
                                                    </p>

                                                </div>

                                            </div>

                                        @endif


                                        {{-- Location --}}
                                        @if($post->event_location)

                                            <div class="flex items-start gap-3 sm:col-span-2">

                                                <div class="mt-0.5 text-indigo-600">
                                                    <i class="fa-solid fa-location-dot"></i>
                                                </div>

                                                <div>

                                                    <p class="text-xs font-medium text-gray-500">
                                                        Location
                                                    </p>

                                                    <p class="mt-0.5 text-sm font-semibold text-gray-900">
                                                        {{ $post->event_location }}
                                                    </p>

                                                </div>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            @endif


                            {{-- ================================================= --}}
                            {{-- ARTICLE BODY --}}
                            {{-- ================================================= --}}

                            <div
                                class="prose prose-gray max-w-none
                                       prose-headings:font-bold
                                       prose-a:text-indigo-600
                                       prose-a:no-underline
                                       hover:prose-a:underline
                                       prose-img:rounded-xl"
                            >
                                {!! $post->content !!}
                            </div>


                            {{-- ================================================= --}}
                            {{-- SHARE / BACK --}}
                            {{-- ================================================= --}}

                            <div
                                class="mt-10 flex flex-col gap-4 border-t
                                       border-gray-200 pt-6 sm:flex-row
                                       sm:items-center sm:justify-between"
                            >

                                <a
                                    href="{{ route('home') }}"
                                    class="inline-flex items-center gap-2
                                           text-sm font-semibold text-gray-600
                                           transition hover:text-indigo-600"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Back to Home
                                </a>


                                <div class="flex items-center gap-2">

                                    <span class="text-xs font-medium text-gray-400">
                                        Share
                                    </span>

                                    {{-- Facebook --}}
                                    <a
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex h-9 w-9 items-center justify-center
                                               rounded-lg bg-gray-100 text-gray-600
                                               transition hover:bg-indigo-100
                                               hover:text-indigo-600"
                                        title="Share on Facebook"
                                    >
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>


                                    {{-- Copy Link --}}
                                    <button
                                        type="button"
                                        x-data
                                        @click="
                                            navigator.clipboard.writeText(window.location.href);
                                            $dispatch('link-copied');
                                        "
                                        class="flex h-9 w-9 items-center justify-center
                                               rounded-lg bg-gray-100 text-gray-600
                                               transition hover:bg-indigo-100
                                               hover:text-indigo-600"
                                        title="Copy link"
                                    >
                                        <i class="fa-solid fa-link"></i>
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </article>


                {{-- ================================================= --}}
                {{-- SIDEBAR --}}
                {{-- ================================================= --}}

                <aside class="lg:col-span-1">

                    <div class="space-y-6">


                        {{-- ================================================= --}}
                        {{-- POST TYPE INFO --}}
                        {{-- ================================================= --}}

                        <div
                            class="rounded-xl border border-gray-200
                                   bg-white p-5 shadow-sm"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center
                                           justify-center rounded-lg
                                           bg-indigo-50 text-indigo-600"
                                >

                                    @if($post->type === 'announcement')
                                        <i class="fa-solid fa-bullhorn"></i>
                                    @else
                                        <i class="fa-solid fa-newspaper"></i>
                                    @endif

                                </div>

                                <div>

                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Category
                                    </p>

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ ucfirst($post->type) }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- EVENT CTA --}}
                        {{-- ================================================= --}}

                        @if($post->is_event && $post->event_date)

                            <div
                                class="rounded-xl border border-indigo-200
                                       bg-indigo-50 p-5"
                            >

                                <div class="flex items-start gap-3">

                                    <div
                                        class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-lg bg-indigo-600
                                               text-white"
                                    >
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>

                                    <div>

                                        <h3 class="font-bold text-gray-900">
                                            Don't miss this event
                                        </h3>

                                        <p class="mt-1 text-xs leading-5 text-gray-600">
                                            Mark your calendar and join your
                                            fellow residents.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- BARANGAY INFORMATION --}}
                        {{-- ================================================= --}}

                        <div
                            class="rounded-xl border border-gray-200
                                   bg-white p-5 shadow-sm"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center
                                           justify-center rounded-lg
                                           bg-emerald-50 text-emerald-600"
                                >
                                    <i class="fa-solid fa-building-columns"></i>
                                </div>

                                <div>

                                    <h3 class="font-bold text-gray-900">
                                        Barangay San Lorenzo Ruiz
                                    </h3>

                                    <p class="mt-1 text-xs text-gray-500">
                                        BrgyConnect Information System
                                    </p>

                                </div>

                            </div>


                            <div class="mt-5 space-y-3">

                                <div class="flex items-start gap-3 text-sm">

                                    <i class="fa-solid fa-location-dot mt-0.5 w-4 text-center text-gray-400"></i>

                                    <span class="text-gray-600">
                                        Barangay Hall
                                    </span>

                                </div>


                                <div class="flex items-start gap-3 text-sm">

                                    <i class="fa-solid fa-phone mt-0.5 w-4 text-center text-gray-400"></i>

                                    <span class="text-gray-600">
                                        Barangay Hotline
                                    </span>

                                </div>


                                <div class="flex items-start gap-3 text-sm">

                                    <i class="fa-solid fa-clock mt-0.5 w-4 text-center text-gray-400"></i>

                                    <span class="text-gray-600">
                                        Monday – Friday
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- BACK TO ANNOUNCEMENTS --}}
                        {{-- ================================================= --}}

                        <a
                            href="{{ route('home') }}#announcements"
                            class="flex items-center justify-between rounded-xl
                                   border border-gray-200 bg-white p-4
                                   text-sm font-semibold text-gray-700
                                   shadow-sm transition hover:border-indigo-200
                                   hover:text-indigo-600"
                        >

                            <span class="flex items-center gap-2">

                                <i class="fa-solid fa-bullhorn"></i>

                                More Announcements

                            </span>

                            <i class="fa-solid fa-arrow-right text-xs"></i>

                        </a>

                    </div>

                </aside>

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