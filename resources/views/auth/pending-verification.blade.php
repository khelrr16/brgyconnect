<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Account Verification - BrgyConnect</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    <div class="min-h-screen flex items-center justify-center px-6 py-12">

        <div class="w-full max-w-md">

            {{-- Logo / Branding --}}
            <div class="text-center mb-8">

                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 rounded-full bg-blue-100
                                flex items-center justify-center">

                        <i class="fa-solid fa-shield-halved
                                  text-3xl text-blue-600"></i>

                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-800">
                    BrgyConnect
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Barangay Information System
                </p>

            </div>


            {{-- Verification Card --}}
            <div class="bg-white rounded-2xl shadow-sm
                        border border-gray-200 p-8 text-center">

                {{-- Icon --}}
                <div class="flex justify-center mb-5">

                    <div class="w-16 h-16 rounded-full
                                bg-yellow-100
                                flex items-center justify-center">

                        <i class="fa-solid fa-clock
                                  text-2xl text-yellow-600"></i>

                    </div>

                </div>


                {{-- Message --}}
                <h2 class="text-xl font-semibold text-gray-800">
                    Account Verification Pending
                </h2>

                <p class="mt-3 text-gray-600 leading-relaxed">
                    Your account has been successfully registered.
                    Please wait for the administrator to verify and
                    approve your account.
                </p>

                <p class="mt-3 text-sm text-gray-500">
                    You will be able to access BrgyConnect once your
                    account has been verified.
                </p>


                {{-- Divider --}}
                <div class="my-6 border-t border-gray-200"></div>


                {{-- Status --}}
                <div class="inline-flex items-center gap-2
                            px-4 py-2 rounded-full
                            bg-yellow-50 text-yellow-700
                            text-sm font-medium">

                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>

                    Waiting for administrator approval

                </div>


                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-7">
                    @csrf

                    <button
                        type="submit"
                        class="text-sm text-gray-500
                               hover:text-gray-700
                               hover:underline"
                    >
                        <i class="fa-solid fa-right-from-bracket mr-1"></i>
                        Logout
                    </button>

                </form>

            </div>


            {{-- Footer --}}
            <p class="text-center text-xs text-gray-400 mt-6">
                © {{ date('Y') }} BrgyConnect.
                All rights reserved.
            </p>

        </div>

    </div>

</body>

</html>