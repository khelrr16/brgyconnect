<x-guest-layout>

    <div class="min-h-screen bg-gray-50 py-12 sm:px-1 lg:px-8">

        <div class="mx-auto grid max-w-5xl bg-gray-200 p-5 rounded-xl lg:grid-cols-2">

            {{-- ================================================= --}}
            {{-- LEFT / BRANDING --}}
            {{-- ================================================= --}}

            <div class="relative hidden overflow-hidden p-5 rounded-xl bg-indigo-700 lg:block">

                {{-- Background decoration --}}
                <div class="absolute -right-32 -top-64 h-96 w-96 rounded-full bg-indigo-600"></div> 
                <div class="absolute -bottom-60 -left-20 h-[28rem] w-[28rem] rounded-full bg-indigo-800"></div>

                <div class="relative flex w-full flex-col text-white font-black px-12 xl:px-20">
                    <div class="flex w-full justify-between items-center">
                        <div>
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </div>

                        <a
                            href="{{ route('home') }}"
                            class="flex"
                        >
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full
                                    bg-white/10 px-3 py-1.5
                                    text-sm font-black
                                    tracking-wider text-indigo-100
                                    ring-1 ring-inset ring-white/20"
                            >
                                Back to home <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                    

                    <h1 class="mt-6 text-4xl font-bold leading-tight text-white xl:text-5xl">
                        Your barangay,
                        <span class="text-indigo-200">
                            connected.
                        </span>
                    </h1>

                    <p class="mt-5 max-w-xl text-sm leading-7 text-indigo-100 xl:text-base">
                        Access barangay services, stay informed about community
                        announcements, and manage your resident information
                        through one convenient platform.
                    </p>


                    {{-- Feature highlights --}}
                    <div class="mt-8 space-y-4">

                        <div class="flex items-center gap-3 text-sm text-indigo-100">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>

                            <span>
                                Request barangay certificates online
                            </span>

                        </div>


                        <div class="flex items-center gap-3 text-sm text-indigo-100">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                <i class="fa-solid fa-bullhorn"></i>
                            </div>

                            <span>
                                Stay updated with barangay announcements
                            </span>

                        </div>


                        <div class="flex items-center gap-3 text-sm text-indigo-100">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>

                            <span>
                                Secure resident account access
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RIGHT / LOGIN FORM --}}
            {{-- ================================================= --}}

            <div class="flex items-center justify-center py-5">

                <div class="w-full max-w-md p-5">

                    {{-- Mobile logo --}}
                    <a href="{{ route('home') }}">
                        <div class="mb-8 flex flex-col items-center text-center lg:hidden">
                            <img
                                src="{{ asset('images/brgy_logo.png') }}"
                                alt="BrgyConnect"
                                class="h-16 w-16 object-contain"
                            >

                            <h1 class="mt-3 text-2xl font-bold text-gray-900">
                                BrgyConnect
                            </h1>

                            <p class="mt-1 text-sm text-gray-500">
                                Barangay Information System
                            </p>

                        </div>
                    </a>


                    {{-- Heading --}}
                    <div class="mb-8">

                        <p class="text-sm font-semibold text-indigo-600">
                            Welcome back
                        </p>

                        <h2 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">
                            Sign in to your account
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            Access your BrgyConnect account and barangay services.
                        </p>

                    </div>


                    {{-- Session Status --}}
                    <x-auth-session-status
                        class="mb-4"
                        :status="session('status')"
                    />


                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">

                        @csrf


                        {{-- Email --}}
                        <div>

                            <x-input-label
                                for="email"
                                value="Email Address"
                                class="font-medium text-gray-700"
                            />

                            <div class="relative mt-1.5">

                                <i
                                    class="fa-solid fa-envelope
                                           pointer-events-none
                                           absolute left-3 top-1/2
                                           -translate-y-1/2
                                           text-gray-400"
                                ></i>

                                <x-text-input
                                    id="email"
                                    name="email"
                                    type="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                    class="block w-full rounded-lg border-gray-300 py-3 pl-10 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />

                            </div>

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-1.5"
                            />

                        </div>


                        {{-- Password --}}
                        <div>

                            <div class="flex items-center justify-between">

                                <x-input-label
                                    for="password"
                                    value="Password"
                                    class="font-medium text-gray-700"
                                />

                                @if (Route::has('password.request'))

                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800"
                                    >
                                        Forgot password?
                                    </a>

                                @endif

                            </div>


                            <div
                                x-data="{ show: false }"
                                class="relative mt-1.5">

                                <i
                                    class="fa-solid fa-lock
                                           pointer-events-none
                                           absolute left-3 top-1/2
                                           -translate-y-1/2
                                           text-gray-400"
                                ></i>

                                <input
                                    id="password"
                                    name="password"
                                    x-bind:type="show ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="block w-full rounded-lg border-gray-300
                                           py-3 pl-10 pr-11 shadow-sm
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                >

                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    tabindex="-1"
                                >
                                    <i
                                        class="fa-solid"
                                        :class="show ? 'fa-eye-slash' : 'fa-eye'"
                                    ></i>
                                </button>

                            </div>

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-1.5"
                            />

                        </div>


                        {{-- Remember --}}
                        <div class="flex items-center">

                            <label class="inline-flex items-center">

                                <input
                                    id="remember"
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-gray-300 text-indigo-600
                                           shadow-sm focus:ring-indigo-500"
                                >

                                <span class="ml-2 text-sm text-gray-600">
                                    Remember me
                                </span>

                            </label>

                        </div>


                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2
                                   rounded-lg
                                   border border-indigo-700
                                   bg-indigo-600
                                   px-5 py-3
                                   text-sm font-semibold text-white
                                   shadow-md
                                   transition-all
                                   hover:bg-indigo-700
                                   hover:shadow-lg
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-500
                                   focus:ring-offset-2"
                        >
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Sign In
                        </button>


                        {{-- Register --}}
                        @if (Route::has('register'))

                            <p class="text-center text-sm text-gray-500">

                                Don't have an account?

                                <a
                                    href="{{ route('register') }}"
                                    class="font-semibold text-indigo-600 hover:text-indigo-800"
                                >
                                    Create one
                                </a>

                            </p>

                        @endif

                    </form>


                    {{-- Security --}}
                    <div class="mt-8 flex items-center justify-center gap-2 text-xs text-gray-400">

                        <i class="fa-solid fa-shield-halved"></i>

                        <span>
                            Your account information is securely protected.
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>