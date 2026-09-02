<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold uppercase tracking-tight text-gray-800">
                Edit Post
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Update this news article or announcement.
            </p>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            @if ($errors->any())

                <div
                    class="mb-6 rounded-lg border border-red-200
                           bg-red-50 px-4 py-4 text-red-800"
                >

                    <div class="flex items-start gap-3">

                        <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-600"></i>

                        <div>

                            <p class="text-sm font-semibold">
                                Please correct the following errors:
                            </p>

                            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">

                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif

            <form
                method="POST"
                action="{{ route('admin.posts.update', $post) }}"
                enctype="multipart/form-data"
                class="space-y-6"
            >

                @csrf
                @method('PATCH')


                {{-- ================================================= --}}
                {{-- POST TYPE --}}
                {{-- ================================================= --}}

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-5">

                        <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">
                            Post Type
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Choose whether this is a news article or announcement.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                        {{-- News --}}
                        <label class="relative cursor-pointer">

                            <input
                                type="radio"
                                name="type"
                                value="news"
                                class="peer sr-only"
                                {{ old('type', $post->type) === 'news' ? 'checked' : '' }}
                                required
                            >

                            <div
                                class="rounded-xl border-2 border-gray-200
                                       bg-white p-5
                                       transition
                                       hover:border-gray-300
                                       peer-checked:border-indigo-600
                                       peer-checked:bg-indigo-50">

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


                        {{-- Announcement --}}
                        <label class="relative cursor-pointer">

                            <input
                                type="radio"
                                name="type"
                                value="announcement"
                                class="peer sr-only"
                                {{ old('type', $post->type) === 'announcement' ? 'checked' : '' }}
                            >

                            <div
                                class="rounded-xl border-2 border-gray-200
                                       bg-white p-5
                                       transition
                                       hover:border-gray-300
                                       peer-checked:border-red-600
                                       peer-checked:bg-red-50"
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
                            required
                        >
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


                {{-- ================================================= --}}
                {{-- ARTICLE INFORMATION --}}
                {{-- ================================================= --}}

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-5">

                        <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">
                            Article Information
                        </h3>

                    </div>


                    <div class="space-y-5">


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
                                value="{{ old('title', $post->title) }}"
                                required
                            />

                            @error('title')

                                <p class="mt-1 text-sm text-red-600">
                                    <i class="fa-solid fa-circle-exclamation"></i>
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
                            >{{ old('excerpt', $post->excerpt) }}</textarea>

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
                                rows="14"
                                required
                                placeholder="Write the full article here..."
                                class="mt-1 block w-full rounded-lg
                                       border-gray-300
                                       shadow-sm
                                       focus:border-indigo-500
                                       focus:ring-indigo-500"
                            >{{ old('content', $post->content) }}</textarea>

                            @error('content')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- CURRENT IMAGE --}}
                        {{-- ================================================= --}}

                        @if($post->image)

                            <div>

                                <x-input-label
                                    value="Current Featured Image"
                                />

                                <div
                                    class="mt-2 overflow-hidden
                                           rounded-lg border border-gray-200
                                           bg-gray-50"
                                >

                                    <img
                                        src="{{ Storage::url($post->image) }}"
                                        alt="{{ $post->title }}"
                                        class="h-56 w-full object-cover"
                                    >

                                </div>


                                <p class="mt-2 text-xs text-gray-400">
                                    Upload a new image below to replace the current one.
                                </p>

                            </div>

                        @endif


                        {{-- New Image --}}
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
                                       bg-white text-sm text-gray-700
                                       file:mr-4
                                       file:border-0
                                       file:bg-gray-100
                                       file:px-4
                                       file:py-2.5
                                       file:font-medium
                                       hover:file:bg-gray-200"
                            >

                            <p class="mt-1 text-xs text-gray-400">
                                Leave blank to keep the current image.
                                JPG, PNG, or WebP — max 5 MB.
                            </p>

                            @error('image')

                                <p class="mt-1 text-sm text-red-600">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PUBLISHING --}}
                {{-- ================================================= --}}

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-5">

                        <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">
                            Publishing
                        </h3>

                    </div>


                    <div class="space-y-5">


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
                                       border-gray-300
                                       shadow-sm
                                       focus:border-indigo-500
                                       focus:ring-indigo-500"
                                required
                            >

                                <option
                                    value="draft"
                                    {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}
                                >
                                    Draft
                                </option>

                                <option
                                    value="published"
                                    {{ old('status', $post->status) === 'published' ? 'selected' : '' }}
                                >
                                    Published
                                </option>

                                <option
                                    value="archived"
                                    {{ old('status', $post->status) === 'archived' ? 'selected' : '' }}
                                >
                                    Archived
                                </option>

                            </select>

                        </div>


                        {{-- Pinned --}}
                        <label class="flex cursor-pointer items-start gap-3">

                            <input
                                type="checkbox"
                                name="is_pinned"
                                value="1"
                                class="mt-1 rounded border-gray-300
                                       text-indigo-600
                                       focus:ring-indigo-500"
                                {{ old('is_pinned', $post->is_pinned) ? 'checked' : '' }}
                            >

                            <span>

                                <span class="block text-sm font-semibold text-gray-800">
                                    Pin this post
                                </span>

                                <span class="block text-xs text-gray-500">
                                    Keep this post at the top of the homepage
                                    when appropriate.
                                </span>

                            </span>

                        </label>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ACTIONS --}}
                {{-- ================================================= --}}

                <div class="flex items-center justify-between gap-3">

                    <a
                        href="{{ route('admin.posts.index') }}"
                        class="inline-flex items-center gap-2
                               rounded-lg border border-gray-300
                               bg-white px-4 py-2.5
                               text-sm font-medium text-gray-700
                               shadow-sm hover:bg-gray-50"
                    >
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>


                    <div class="flex items-center gap-3">

                        <a
                            href="{{ route('admin.posts.index') }}"
                            class="inline-flex items-center gap-2
                                   rounded-lg border border-gray-300
                                   bg-white px-4 py-2.5
                                   text-sm font-medium text-gray-700
                                   shadow-sm hover:bg-gray-50"
                        >
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
                                   shadow-md
                                   hover:bg-indigo-700
                                   hover:shadow-lg
                                   transition"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save Changes
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>