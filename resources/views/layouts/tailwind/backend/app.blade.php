<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @stack('styles')
    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body>

<div class="h-screen flex overflow-hidden bg-gray-100">

    <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
@include('layouts.tailwind.backend.menu-mobile')


<!-- Static sidebar for desktop -->
    @include('layouts.tailwind.backend.sidebar')


    <div class="flex flex-col w-0 flex-1 overflow-hidden">

        @include('layouts.tailwind.backend.top-nav')

        <main class="flex-1 relative overflow-y-auto focus:outline-none">

            <x-jet-banner/>

            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <!-- Replace with your content -->

                {{ $slot }}

                <!-- /End replace -->

                </div>
            </div>

        </main>

    </div>

</div>

@stack('modals')
@stack('js')

@livewireScripts
</body>
</html>
