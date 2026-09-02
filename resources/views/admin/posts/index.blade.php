<x-app-layout>

    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'News & Announcements',
                ],
            ]"
        />

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    News & Announcements
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Create and manage content shown to residents.
                </p>
            </div>

            <a
                href="{{ route('admin.posts.create') }}"
                class="inline-flex items-center gap-2
                       rounded-lg
                       border border-indigo-700
                       bg-indigo-600
                       px-4 py-2.5
                       text-sm font-semibold text-white
                       shadow-md
                       hover:bg-indigo-700
                       hover:shadow-lg"
            >
                <i class="fa-solid fa-plus"></i>
                New Post
            </a>
        </div>

    </x-slot>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if(session('success'))

            <div class="mb-6 rounded-lg border border-emerald-200
                        bg-emerald-50 px-4 py-3 text-emerald-800">

                <i class="fa-solid fa-circle-check mr-2"></i>

                {{ session('success') }}

            </div>

        @endif


        <div class="overflow-hidden rounded-xl border
                    border-gray-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs
                                        font-semibold uppercase tracking-wide
                                        text-gray-500">
                                Post
                            </th>

                            <th class="px-6 py-4 text-left text-xs
                                        font-semibold uppercase tracking-wide
                                        text-gray-500">
                                Type
                            </th>

                            <th class="px-6 py-4 text-left text-xs
                                        font-semibold uppercase tracking-wide
                                        text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-left text-xs
                                        font-semibold uppercase tracking-wide
                                        text-gray-500">
                                Author
                            </th>

                            <th class="px-6 py-4 text-right text-xs
                                        font-semibold uppercase tracking-wide
                                        text-gray-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($posts as $post)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        @if($post->image)

                                            <img
                                                src="{{ Storage::url($post->image) }}"
                                                class="h-12 w-16 rounded-lg object-cover"
                                                alt=""
                                            >

                                        @else

                                            <div
                                                class="flex h-12 w-16 items-center
                                                        justify-center rounded-lg
                                                        bg-gray-100 text-gray-400"
                                            >
                                                <i class="fa-solid fa-image"></i>
                                            </div>

                                        @endif


                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $post->title }}
                                            </p>

                                            @if($post->is_pinned)

                                                <span class="text-xs text-amber-600">
                                                    <i class="fa-solid fa-thumbtack"></i>
                                                    Pinned
                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    @if($post->type === 'announcement')

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                    rounded-full bg-red-50
                                                    px-3 py-1 text-xs
                                                    font-semibold text-red-700"
                                        >
                                            <i class="fa-solid fa-bullhorn"></i>
                                            Announcement
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                    rounded-full bg-indigo-50
                                                    px-3 py-1 text-xs
                                                    font-semibold text-indigo-700"
                                        >
                                            <i class="fa-solid fa-newspaper"></i>
                                            News
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    @if($post->status === 'published')

                                        <span class="inline-flex items-center gap-1.5
                                                        rounded-full bg-emerald-50
                                                        px-3 py-1 text-xs
                                                        font-semibold text-emerald-700">
                                            <i class="fa-solid fa-circle-check"></i>
                                            Published
                                        </span>

                                    @elseif($post->status === 'draft')

                                        <span class="inline-flex items-center gap-1.5
                                                        rounded-full bg-gray-100
                                                        px-3 py-1 text-xs
                                                        font-semibold text-gray-600">
                                            <i class="fa-solid fa-file"></i>
                                            Draft
                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5
                                                        rounded-full bg-amber-50
                                                        px-3 py-1 text-xs
                                                        font-semibold text-amber-700">
                                            <i class="fa-solid fa-box-archive"></i>
                                            Archived
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $post->author->name }}
                                </td>


                                <td class="px-6 py-4 text-right">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ route('admin.posts.edit', $post) }}"
                                            class="inline-flex items-center gap-2
                                                    rounded-lg border border-gray-300
                                                    bg-white px-3 py-2
                                                    text-sm font-medium text-gray-700
                                                    shadow-sm hover:bg-gray-50"
                                        >
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            Edit
                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('admin.posts.destroy', $post) }}"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                onclick="return confirm('Delete this post?')"
                                                class="inline-flex items-center gap-2
                                                        rounded-lg border border-red-200
                                                        bg-red-50 px-3 py-2
                                                        text-sm font-medium text-red-700
                                                        hover:bg-red-100"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="px-6 py-12 text-center text-gray-500">

                                    <i class="fa-solid fa-newspaper text-3xl text-gray-300"></i>

                                    <p class="mt-3 font-medium">
                                        No posts yet.
                                    </p>

                                    <p class="mt-1 text-sm">
                                        Create your first news article or announcement.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="border-t border-gray-100 px-6 py-4">
                {{ $posts->links() }}
            </div>

        </div>

    </div>

</x-app-layout>