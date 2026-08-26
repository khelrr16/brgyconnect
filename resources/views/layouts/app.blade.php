<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 4000)"
                x-show="show"
                x-transition
                class="fixed right-6 top-6 z-50 flex items-center gap-3 rounded-lg bg-green-600 px-5 py-3 text-white shadow-lg"
                role="status"
            >
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
                <button
                    type="button"
                    @click="show = false"
                    class="ml-2 text-xl leading-none"
                    aria-label="Dismiss notification"
                >
                    &times;
                </button>
            </div>
        @endif

        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="pt-12 lg:pl-64 lg:pt-0">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
