<x-guest-layout>

    <div class="min-h-[calc(100vh-4rem)]">

        <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl lg:grid-cols-2">

            {{-- ================================================= --}}
            {{-- LEFT --}}
            {{-- ================================================= --}}

            <div class="relative hidden overflow-hidden bg-indigo-700 lg:flex">

                <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-indigo-600"></div>

                <div class="absolute -bottom-40 -right-20 h-[28rem] w-[28rem] rounded-full bg-indigo-800"></div>


                <div class="relative flex w-full flex-col justify-center px-12 xl:px-20">

                    <div
                        class="flex h-14 w-14 items-center justify-center
                               rounded-2xl bg-white/10
                               text-white ring-1 ring-inset ring-white/20"
                    >
                        <i class="fa-solid fa-user-plus text-xl"></i>
                    </div>


                    <h1 class="mt-6 text-4xl font-bold leading-tight text-white xl:text-5xl">
                        Join
                        <span class="text-indigo-200">
                            BrgyConnect.
                        </span>
                    </h1>


                    <p class="mt-5 max-w-xl text-sm leading-7 text-indigo-100 xl:text-base">
                        Create your account to access online barangay services
                        and stay connected with your local community.
                    </p>


                    <div class="mt-8 grid grid-cols-2 gap-4">

                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">

                            <i class="fa-solid fa-file-circle-check text-indigo-200"></i>

                            <p class="mt-3 text-sm font-semibold text-white">
                                Online Services
                            </p>

                            <p class="mt-1 text-xs leading-5 text-indigo-200">
                                Request available barangay services online.
                            </p>

                        </div>


                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">

                            <i class="fa-solid fa-bullhorn text-indigo-200"></i>

                            <p class="mt-3 text-sm font-semibold text-white">
                                Community Updates
                            </p>

                            <p class="mt-1 text-xs leading-5 text-indigo-200">
                                Stay informed about barangay activities.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- REGISTER FORM --}}
            {{-- ================================================= --}}

            <div class="flex items-center justify-center px-6 py-10 sm:px-10 lg:px-12">

                <div class="w-full max-w-md">

                    {{-- Mobile --}}
                    <div class="mb-7 flex flex-col items-center text-center lg:hidden">

                        <img
                            src="{{ asset('images/brgy_logo.png') }}"
                            alt="BrgyConnect"
                            class="h-14 w-14 object-contain"
                        >

                        <h1 class="mt-3 text-2xl font-bold text-gray-900">
                            Create your account
                        </h1>

                    </div>


                    <div class="mb-7">

                        <p class="text-sm font-semibold text-indigo-600">
                            Get started
                        </p>

                        <h2 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">
                            Create an account
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            After registration, your account will need to be
                            verified by the barangay.
                        </p>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('register') }}"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- Name --}}
                        <div>

                            <x-input-label
                                for="name"
                                value="Full Name"
                                class="font-medium text-gray-700"
                            />

                            <div class="relative mt-1.5">

                                <i
                                    class="fa-solid fa-user
                                           pointer-events-none
                                           absolute left-3 top-1/2
                                           -translate-y-1/2
                                           text-gray-400"
                                ></i>

                                <x-text-input
                                    id="name"
                                    name="name"
                                    type="text"
                                    :value="old('name')"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Enter your full name"
                                    class="block w-full rounded-lg border-gray-300
                                           py-3 pl-10 shadow-sm
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                />

                            </div>

                            <x-input-error
                                :messages="$errors->get('name')"
                                class="mt-1.5"
                            />

                        </div>


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
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                    class="block w-full rounded-lg border-gray-300
                                           py-3 pl-10 shadow-sm
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                />

                            </div>

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-1.5"
                            />

                        </div>


                        {{-- Password --}}
                        <div>

                            <x-input-label
                                for="password"
                                value="Password"
                                class="font-medium text-gray-700"
                            />

                            <div
                                x-data="{ show: false }"
                                class="relative mt-1.5"
                            >

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
                                    autocomplete="new-password"
                                    placeholder="Create a password"
                                    class="block w-full rounded-lg border-gray-300
                                           py-3 pl-10 pr-11 shadow-sm
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                >

                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
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


                        {{-- Confirm --}}
                        <div>

                            <x-input-label
                                for="password_confirmation"
                                value="Confirm Password"
                                class="font-medium text-gray-700"
                            />

                            <div class="relative mt-1.5">

                                <i
                                    class="fa-solid fa-lock
                                           pointer-events-none
                                           absolute left-3 top-1/2
                                           -translate-y-1/2
                                           text-gray-400"
                                ></i>

                                <x-text-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Confirm your password"
                                    class="block w-full rounded-lg border-gray-300
                                           py-3 pl-10 shadow-sm
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                />

                            </div>

                        </div>


                        {{-- Verification notice --}}
                        <div
                            class="rounded-lg border border-indigo-200
                                   bg-indigo-50 px-4 py-3"
                        >

                            <div class="flex items-start gap-3">

                                <i
                                    class="fa-solid fa-user-shield
                                           mt-0.5 text-indigo-600"
                                ></i>

                                <div>

                                    <p class="text-xs font-semibold text-indigo-900">
                                        Account verification required
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-indigo-700">
                                        After creating your account, you will be
                                        asked to provide your resident information
                                        for barangay verification.
                                    </p>

                                </div>

                            </div>

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

                            <i class="fa-solid fa-user-plus"></i>

                            Create Account

                        </button>


                        {{-- Login --}}
                        <p class="text-center text-sm text-gray-500">

                            Already have an account?

                            <a
                                href="{{ route('login') }}"
                                class="font-semibold text-indigo-600 hover:text-indigo-800"
                            >
                                Sign in
                            </a>

                        </p>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>