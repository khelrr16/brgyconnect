<x-app-layout>

    <div class="min-h-screen bg-gray-50">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="border-b border-gray-200 bg-white">

            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Resident Management
                        </p>

                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                            Households
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Manage household addresses and their members.
                        </p>

                    </div>


                    <a
                        href="{{ route('admin.households.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg
                               bg-indigo-600 px-4 py-2.5 text-sm font-semibold
                               text-white shadow-sm transition
                               hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Add Household
                    </a>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- CONTENT --}}
        {{-- ================================================= --}}

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- Search --}}
            <div class="mb-6">

                <form
                    method="GET"
                    action="{{ route('admin.households.index') }}"
                    class="relative max-w-md"
                >

                    <i
                        class="fa-solid fa-magnifying-glass
                               pointer-events-none absolute left-3 top-1/2
                               -translate-y-1/2 text-gray-400"
                    ></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search household or address..."
                        class="w-full rounded-lg border-gray-300 pl-10 pr-4
                               text-sm shadow-sm
                               focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </form>

            </div>


            {{-- ================================================= --}}
            {{-- HOUSEHOLDS TABLE --}}
            {{-- ================================================= --}}

            <div
                class="overflow-hidden rounded-xl border border-gray-200
                       bg-white shadow-sm"
            >

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wider
                                           text-gray-500"
                                >
                                    Household
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wider
                                           text-gray-500"
                                >
                                    Address
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wider
                                           text-gray-500"
                                >
                                    Members
                                </th>

                                <th
                                    class="px-6 py-3 text-right text-xs
                                           font-semibold uppercase tracking-wider
                                           text-gray-500"
                                >
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse($households as $household)

                                <tr class="transition hover:bg-gray-50">

                                    {{-- Household --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-10 w-10 items-center
                                                       justify-center rounded-lg
                                                       bg-indigo-50 text-indigo-600"
                                            >
                                                <i class="fa-solid fa-house"></i>
                                            </div>

                                            <div>

                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $household->household_id }}
                                                </p>

                                                <p class="text-xs text-gray-500">
                                                    Registered
                                                    {{ $household->created_at->format('M d, Y') }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Address --}}
                                    <td class="px-6 py-4">

                                        <p class="text-sm text-gray-900">
                                            {{ $household->full_address }}
                                        </p>

                                    </td>


                                    {{-- Members --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-gray-100
                                                   px-2.5 py-1 text-xs
                                                   font-semibold text-gray-700"
                                        >

                                            <i class="fa-solid fa-users"></i>

                                            {{ $household->residents_count }}
                                            {{ Str::plural('member', $household->residents_count) }}

                                        </span>

                                    </td>


                                    {{-- Action --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-right">

                                        <a
                                            href="{{ route('admin.households.show', $household) }}"
                                            class="inline-flex items-center gap-2
                                                   rounded-lg border border-gray-200
                                                   bg-white px-3 py-2 text-xs
                                                   font-semibold text-gray-700
                                                   transition hover:border-indigo-200
                                                   hover:bg-indigo-50 hover:text-indigo-600"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="px-6 py-16 text-center">

                                        <div
                                            class="mx-auto flex h-14 w-14 items-center
                                                   justify-center rounded-full
                                                   bg-gray-100 text-gray-400"
                                        >
                                            <i class="fa-solid fa-house text-xl"></i>
                                        </div>

                                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                                            No households found
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Start by creating a new household.
                                        </p>

                                        <a
                                            href="{{ route('admin.households.create') }}"
                                            class="mt-5 inline-flex items-center gap-2
                                                   rounded-lg bg-indigo-600
                                                   px-4 py-2.5 text-sm font-semibold
                                                   text-white hover:bg-indigo-700"
                                        >
                                            <i class="fa-solid fa-plus"></i>
                                            Add Household
                                        </a>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($households->hasPages())

                    <div class="border-t border-gray-200 px-6 py-4">

                        {{ $households->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>