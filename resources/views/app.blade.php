<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @if(app()->environment('production'))
            <link rel="stylesheet" href="{{ asset('build/assets/app-ClGCl7rV.css') }}">
            <script type="module" src="{{ asset('build/assets/app-tav4KXaI.js') }}"></script>
        @else
            @vite('resources/js/app.js')
        @endif
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-white dark:bg-slate-900 text-slate-900 dark:text-white transition-colors duration-200">
        @inertia
    </body>
</html>
