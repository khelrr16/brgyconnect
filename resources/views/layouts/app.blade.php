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

        @php
            $notification = null;

            if (session('success')) {
                $notification = [
                    'message' => session('success'),
                    'type' => 'success',
                    'icon' => 'fa-circle-check',
                    'classes' => 'bg-green-600',
                ];
            } elseif (session('error')) {
                $notification = [
                    'message' => session('error'),
                    'type' => 'error',
                    'icon' => 'fa-circle-xmark',
                    'classes' => 'bg-red-600',
                ];
            } elseif (session('warning')) {
                $notification = [
                    'message' => session('warning'),
                    'type' => 'warning',
                    'icon' => 'fa-triangle-exclamation',
                    'classes' => 'bg-amber-500',
                ];
            } elseif (session('info')) {
                $notification = [
                    'message' => session('info'),
                    'type' => 'info',
                    'icon' => 'fa-circle-info',
                    'classes' => 'bg-blue-600',
                ];
            }
        @endphp

        @if ($notification)
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 4000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-5"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-5"
                class="fixed right-6 top-6 z-50
                    flex items-center gap-3
                    rounded-lg
                    {{ $notification['classes'] }}
                    px-5 py-3
                    text-white
                    shadow-lg"
                role="alert"
            >
                <i
                    class="fa-solid {{ $notification['icon'] }}"
                    aria-hidden="true"
                ></i>

                <span class="text-sm font-medium">
                    {{ $notification['message'] }}
                </span>

                <button
                    type="button"
                    @click="show = false"
                    class="ml-2 text-lg leading-none
                        opacity-80 hover:opacity-100
                        transition-opacity"
                    aria-label="Dismiss notification"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    
        <div class="min-h-screen">

            @role('super-admin|blotter-officer|resident-officer')
                @include('layouts.sidebar')

                <!-- Page Content -->
                <main class="pt-12 lg:pl-64 lg:pt-0">
                    <div class="px-6 py-8">
                        {{ $header ?? '' }}

                        {{ $slot }}
                    </div>
                </main>
                
            @else
                @include('layouts.navigation')

                <!-- Page Content -->
                <main class="pt-12">
                    {{ $slot }}
                </main>
            @endrole
        </div>

        @livewireScripts
    </body>
</html>
