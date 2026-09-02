<x-app-layout>

    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'News & Announcements',
                    'url' => route('admin.posts.index'),
                ],
                [
                    'label' => 'New Post',
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    New Post
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Publish a news article or announcement for residents.
                </p>
            </div>
        </div>
    </x-slot>

    <div x-data="{ isEvent: {{ old('is_event', false) ? 'true' : 'false' }} }">

        <form
            method="POST"
            action="{{ route('admin.posts.store') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf


            {{-- ============================================ --}}
            {{-- POST TYPE --}}
            {{-- ============================================ --}}

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">
                    Post Type
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Choose how this post will be categorized.
                </p>


                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- NEWS --}}
                    <label class="relative h-full cursor-pointer">

                        <input
                            type="radio"
                            name="type"
                            value="news"
                            class="peer sr-only"
                            {{ old('type') === 'news' ? 'checked' : '' }}
                            required
                        >

                        <div
                            class="h-full rounded-xl border-2 border-gray-200
                                    bg-white p-5
                                    transition
                                    peer-checked:border-indigo-600
                                    peer-checked:bg-indigo-50
                                    hover:border-gray-300"
                        >

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center
                                            justify-center rounded-lg
                                            bg-blue-100 text-blue-600"
                                >
                                    <i class="fa-solid fa-newspaper"></i>
                                </div>

                                <div>

                                    <h4 class="font-bold text-gray-900">
                                        News
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Community updates, projects,
                                        activities, and stories.
                                    </p>

                                </div>

                            </div>

                        </div>

                        <i
                            class="fa-solid fa-circle-check
                                    absolute right-4 top-4
                                    hidden text-indigo-600
                                    peer-checked:block"
                        ></i>

                    </label>


                    {{-- ANNOUNCEMENT --}}
                    <label class="relative h-full cursor-pointer">

                        <input
                            type="radio"
                            name="type"
                            value="announcement"
                            class="peer sr-only"
                            {{ old('type') === 'announcement' ? 'checked' : '' }}
                        >

                        <div
                            class="h-full rounded-xl border-2 border-gray-200
                                    bg-white p-5
                                    transition
                                    peer-checked:border-red-600
                                    peer-checked:bg-red-50
                                    hover:border-gray-300"
                        >

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center
                                            justify-center rounded-lg
                                            bg-red-100 text-red-600"
                                >
                                    <i class="fa-solid fa-bullhorn"></i>
                                </div>

                                <div>

                                    <h4 class="font-bold text-gray-900">
                                        Announcement
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Important notices, schedules,
                                        warnings, and public information.
                                    </p>

                                </div>

                            </div>

                        </div>

                        <i
                            class="fa-solid fa-circle-check
                                    absolute right-4 top-4
                                    hidden text-red-600
                                    peer-checked:block"
                        ></i>

                    </label>

                </div>

                <div class="mt-4 block text-sm font-medium text-gray-700">
                    <label for="category">
                        Category
                    </label>

                    <select
                        id="category"
                        name="category"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>

                        <option value="">--Select Category--</option>
                        <option value="general" {{ old('category', $post->category ?? '') === 'general' ? 'selected' : '' }}>
                            General
                        </option>
                        <option value="important" {{ old('category', $post->category ?? '') === 'important' ? 'selected' : '' }}>
                            Important
                        </option>
                        <option value="community" {{ old('category', $post->category ?? '') === 'community' ? 'selected' : '' }}>
                            Community
                        </option>
                        <option value="program" {{ old('category', $post->category ?? '') === 'program' ? 'selected' : '' }}>
                            Program
                        </option>
                        <option value="notice" {{ old('category', $post->category ?? '') === 'notice' ? 'selected' : '' }}>
                            Notice
                        </option>
                        <option value="event" {{ old('category', $post->category ?? '') === 'event' ? 'selected' : '' }}>
                            Event
                        </option>
                        <option value="other" {{ old('category', $post->category ?? '') === 'other' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>
                </div>

                @error('type')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ============================================ --}}
            {{-- ARTICLE INFORMATION --}}
            {{-- ============================================ --}}

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">
                    Article Information
                </h3>


                <div class="mt-5 space-y-5">


                    {{-- Title --}}
                    <div>

                        <x-input-label
                            for="title"
                            value="Title"
                        />

                        <x-text-input
                            id="title"
                            name="title"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('title') }}"
                            placeholder="Enter article title"
                            required
                        />

                        @error('title')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Excerpt --}}
                    <div>

                        <x-input-label
                            for="excerpt"
                            value="Short Description"
                        />

                        <textarea
                            id="excerpt"
                            name="excerpt"
                            rows="3"
                            maxlength="1000"
                            placeholder="A short description shown on the homepage..."
                            class="mt-1 block w-full rounded-lg
                                    border-gray-300
                                    shadow-sm
                                    focus:border-indigo-500
                                    focus:ring-indigo-500"
                        >{{ old('excerpt') }}</textarea>

                        <p class="mt-1 text-xs text-gray-400">
                            This is displayed on the News/Announcements card.
                        </p>

                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Content --}}
                    <div>

                        <x-input-label
                            for="content"
                            value="Article Content"
                        />

                        <textarea
                            id="content"
                            name="content"
                            rows="12"
                            placeholder="Write the full article here..."
                            class="mt-1 block w-full rounded-lg
                                    border-gray-300
                                    shadow-sm
                                    focus:border-indigo-500
                                    focus:ring-indigo-500"
                            required
                        >{{ old('content') }}</textarea>

                        @error('content')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Image --}}
                    <div>

                        <x-input-label
                            for="image"
                            value="Featured Image"
                        />

                        <input
                            id="image"
                            name="image"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            class="mt-1 block w-full rounded-lg
                                    border border-gray-300
                                    bg-white text-sm
                                    text-gray-700
                                    file:mr-4
                                    file:border-0
                                    file:bg-gray-100
                                    file:px-4
                                    file:py-2.5
                                    file:font-medium
                                    hover:file:bg-gray-200"
                        >

                        <p class="mt-1 text-xs text-gray-400">
                            JPG, PNG, or WebP. Maximum size: 5 MB.
                        </p>

                        @error('image')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- ============================================ --}}
            {{-- EVENT --}}
            {{-- ============================================ --}}
            
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex items-start gap-3">

                    <input
                        type="checkbox"
                        name="is_event"
                        value="1"
                        id="is_event"
                        x-model="isEvent"
                        class="mt-1 rounded border-gray-300 text-indigo-600
                            focus:ring-indigo-500"
                    >

                    <div>

                        <label
                            for="is_event"
                            class="text-sm font-semibold text-gray-900"
                        >
                            This post is an event
                        </label>

                        <p class="mt-1 text-xs text-gray-500">
                            Enable this if the post represents an upcoming barangay event.
                        </p>

                    </div>

                </div>


                {{-- Event Details --}}
                <div
                    x-show="isEvent"
                    x-cloak
                    class="mt-5 rounded-xl border border-indigo-100 bg-indigo-50/50 p-5">

                    <div class="mb-4 flex items-center gap-2">

                        <div
                            class="flex h-9 w-9 items-center justify-center
                                rounded-lg bg-indigo-100 text-indigo-600"
                        >
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>

                        <div>

                            <h3 class="text-sm font-semibold text-gray-900">
                                Event Details
                            </h3>

                            <p class="text-xs text-gray-500">
                                These details will appear in the Upcoming Events section.
                            </p>

                        </div>

                    </div>


                    <div class="grid gap-5 sm:grid-cols-2">

                        {{-- Event Date --}}
                        <div>

                            <label
                                for="event_date"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Event Date
                            </label>

                            <input
                                type="date"
                                name="event_date"
                                id="event_date"
                                value="{{ old('event_date') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                            >

                            @error('event_date')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- End Date --}}
                        <div>

                            <label
                                for="event_end_date"
                                class="block text-sm font-medium text-gray-700"
                            >
                                End Date
                                <span class="font-normal text-gray-400">(Optional)</span>
                            </label>

                            <input
                                type="date"
                                name="event_end_date"
                                id="event_end_date"
                                value="{{ old('event_end_date') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                            >

                            @error('event_end_date')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Event Time --}}
                        <div>

                            <label
                                for="event_time"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Event Time
                            </label>

                            <input
                                type="time"
                                name="event_time"
                                id="event_time"
                                value="{{ old('event_time') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                            >

                            @error('event_time')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Location --}}
                        <div>

                            <label
                                for="event_location"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Event Location
                            </label>

                            <input
                                type="text"
                                name="event_location"
                                id="event_location"
                                value="{{ old('event_location') }}"
                                placeholder="e.g. Barangay Hall"
                                class="mt-1 block w-full rounded-lg border-gray-300
                                    shadow-sm focus:border-indigo-500
                                    focus:ring-indigo-500"
                            >

                            @error('event_location')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ============================================ --}}
            {{-- PUBLISHING --}}
            {{-- ============================================ --}}

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">
                    Publishing
                </h3>


                <div class="mt-5 space-y-5">


                    {{-- Status --}}
                    <div>

                        <x-input-label
                            for="status"
                            value="Status"
                        />

                        <select
                            id="status"
                            name="status"
                            class="mt-1 block w-full rounded-lg
                                    border-gray-300 shadow-sm
                                    focus:border-indigo-500
                                    focus:ring-indigo-500"
                        >

                            <option value="draft">
                                Draft
                            </option>

                            <option
                                value="published"
                                {{ old('status') === 'published' ? 'selected' : '' }}
                            >
                                Published
                            </option>

                            <option
                                value="archived"
                                {{ old('status') === 'archived' ? 'selected' : '' }}
                            >
                                Archived
                            </option>

                        </select>

                    </div>

                    {{-- Pinned --}}
                    <label class="flex items-start gap-3">

                        <input
                            type="checkbox"
                            name="is_pinned"
                            value="1"
                            class="mt-1 rounded border-gray-300
                                    text-indigo-600
                                    focus:ring-indigo-500"
                        >

                        <span>

                            <span class="block text-sm font-semibold text-gray-800">
                                Pin this post
                            </span>

                            <span class="block text-xs text-gray-500">
                                Pinned announcements can be displayed at the
                                top of the resident homepage.
                            </span>

                        </span>

                    </label>

                </div>

            </div>


            {{-- ============================================ --}}
            {{-- ACTIONS --}}
            {{-- ============================================ --}}

            <div class="flex items-center justify-end gap-3">

                <a
                    href="{{ route('admin.posts.index') }}"
                    class="inline-flex items-center gap-2
                            rounded-lg
                            border border-gray-300
                            bg-white
                            px-4 py-2.5
                            text-sm font-medium
                            text-gray-700
                            shadow-sm
                            hover:bg-gray-50"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Cancel
                </a>


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
                            hover:shadow-md">

                    <i class="fa-solid fa-paper-plane"></i>
                    Save Post
                </button>

            </div>

        </form>

    </div>

</x-app-layout>