<nav
    x-data="{ sidebarOpen: false }"
    class="bg-white border-r border-gray-200"
>
    {{-- Mobile top bar --}}
    <div class="fixed inset-x-0 top-0 z-40 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 lg:hidden">

        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>

        <button
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
            aria-label="Toggle navigation"
        >
            <i
                class="fa-solid"
                :class="sidebarOpen ? 'fa-xmark' : 'fa-bars'"
            ></i>
        </button>
    </div>


    {{-- Mobile backdrop --}}
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/40 lg:hidden"
        x-cloak
    ></div>


    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-gray-200 bg-gradient-to-b from-blue-950 via-blue-700 to-blue-950 transition-transform duration-300 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >

        {{-- Logo --}}
        <div class="flex h-20 items-center border-b border-gray-200 px-6">

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3"
            >
                <x-application-logo
                    class="block h-10 w-auto fill-current text-white"
                />

                <div>
                    <p class="text-sm font-bold text-white">
                        Barangay
                    </p>

                    <p class="text-xs text-white">
                        Management System
                    </p>
                </div>
            </a>

        </div>


        {{-- Navigation --}}
        <div class="flex-1 overflow-y-auto px-4 py-6">

            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-white">
                Main Menu
            </p>

            <nav class="space-y-1">

                {{-- Dashboard --}}
                <x-admin-nav-link
                    :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')"
                >
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    <span>Dashboard</span>
                </x-admin-nav-link>

                {{-- User Accounts --}}
                @role('admin')
                    <x-admin-nav-link
                        :href="route('admin.users.index')"
                        :active="request()->routeIs('admin.users.*')"
                    >
                        <i class="fa-solid fa-users w-5 text-center"></i>
                        <span>User Accounts</span>
                    </x-admin-nav-link>
                @endrole

                {{-- Residents --}}
                <x-admin-nav-link
                    :href="route('residents.index')"
                    :active="request()->routeIs('residents.*')"
                >
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span>Residents</span>
                </x-admin-nav-link>

                {{-- Blotter --}}
                <x-admin-nav-link
                    :href="route('blotters.index')"
                    :active="request()->routeIs('blotters.*')"
                >
                    <i class="fa-solid fa-file-lines w-5 text-center"></i>
                    <span>Blotter Records</span>
                </x-admin-nav-link>

            </nav>


            <p class="mb-3 mt-8 px-3 text-xs font-semibold uppercase tracking-wider text-white">
                Account
            </p>

            <nav class="space-y-1">

                {{-- Profile --}}
                <x-admin-nav-link
                    :href="route('profile.edit')"
                    :active="request()->routeIs('profile.*')"
                >
                    <i class="fa-solid fa-user w-5 text-center"></i>
                    <span>Profile</span>
                </x-admin-nav-link>


                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white transition hover:bg-red-50 hover:text-red-600"
                    >
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                        <span>Log Out</span>
                    </button>

                </form>

            </nav>

        </div>

        {{-- User information --}}
        <div class="border-t border-gray-200 p-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="min-w-0">

                    <p class="truncate text-sm font-semibold text-white">
                        {{ Auth::user()->name }}
                    </p>

                    <p class="truncate text-xs text-white">
                        {{ Auth::user()->email }}
                    </p>

                </div>

            </div>

        </div>

    </aside>


    {{-- Spacer for desktop sidebar --}}
    <div class="hidden lg:block lg:w-64"></div>

</nav>