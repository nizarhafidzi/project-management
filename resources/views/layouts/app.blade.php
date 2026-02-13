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
        @stack('styles')
    </head>
    <body class="tw-font-sans tw-antialiased">
        <div class="tw-min-h-screen tw-bg-gray-100">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="tw-bg-white tw-shadow">
                    <div class="tw-max-w-7xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <!-- Global Toast Notification -->
        <div x-data="{ show: false, type: 'success', message: '' }"
             x-on:notify.window="show = true; type = $event.detail.type; message = $event.detail.content; setTimeout(() => show = false, 3000)"
             class="tw-fixed tw-bottom-4 tw-right-4 tw-z-50"
             style="display: none;"
             x-show="show"
             x-transition:enter="tw-transform tw-ease-out tw-duration-300 tw-transition"
             x-transition:enter-start="tw-translate-y-2 tw-opacity-0 sm:tw-translate-y-0 sm:tw-translate-x-2"
             x-transition:enter-end="tw-translate-y-0 tw-opacity-100 sm:tw-translate-x-0"
             x-transition:leave="tw-transition tw-ease-in tw-duration-100"
             x-transition:leave-start="tw-opacity-100"
             x-transition:leave-end="tw-opacity-0">
            <div :class="{ 'tw-bg-green-500': type === 'success', 'tw-bg-red-500': type === 'error', 'tw-bg-blue-500': type === 'info' }"
                 class="tw-rounded-md tw-px-4 tw-py-3 tw-shadow-lg tw-text-white tw-flex tw-items-center">
                <span x-text="message" class="tw-font-medium"></span>
                <button @click="show = false" class="tw-ml-4 tw-text-white hover:tw-text-gray-100">
                    <svg class="tw-h-4 tw-w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
