<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex gap-4">
                
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                @auth
                    <!-- Navigation Links -->
                    @if (!auth()->user()->isVerified())
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('verifications.create')" :active="request()->routeIs('verifications.create')">
                                Verify
                            </x-nav-link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('verifications.status')" :active="request()->routeIs('verifications.status')">
                                Status
                            </x-nav-link>
                        </div>
                    @else
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('certificate.requests.create')" :active="request()->routeIs('certificate.requests.create')">
                                Certificates
                            </x-nav-link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('certificate.assistance.index')" :active="request()->routeIs('assistance.requests.index')">
                                Assistance Requests
                            </x-nav-link>
                        </div>
                    @endif


                @endauth
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('contacts')" :active="request()->routeIs('contacts')">
                        Contact Us
                    </x-nav-link>
                </div>

            </div>

            @auth

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>
                                    {{ Auth::user()->name }} 
                                    <i class="fa-solid fa-angle-down"></i>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(auth()->user()->profile_completed)
                                <x-dropdown-link :href="route('profile.edit')">
                                    Profile
                                </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

            @else

                <div class="flex items-center ms-6">

                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center gap-2
                            rounded-lg
                            bg-indigo-600
                            border border-indigo-700
                            px-3 py-2.5
                            text-sm font-semibold
                            text-white
                            shadow-sm
                            hover:bg-indigo-700
                            hover:shadow-md
                            transition-all duration-150
                            focus:outline-none
                            focus:ring-2
                            focus:ring-indigo-500
                            focus:ring-offset-2"
                    >
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Login
                    </a>

                </div>
            @endauth

            <!-- Hamburger -->
            @auth
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endauth
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                @if (!auth()->user()->isVerified())
                    <x-responsive-nav-link :href="route('verifications.create')" :active="request()->routeIs('verifications.create')">
                        Verify
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('verifications.create')" :active="request()->routeIs('verifications.status')">
                        Status
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profile
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">Test</div>
                    <div class="font-medium text-sm text-gray-500">Test</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profile
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
