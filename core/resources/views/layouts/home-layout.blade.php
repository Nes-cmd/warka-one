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

    <link rel="icon" href="{{ asset('assets/kerbrands/ker-min.png')}}">

    <!-- Scripts -->
    @livewireStyles
    @livewireScripts
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900  antialiased h-screen overflow-hidden justify-center items-center flex-column">
    
    <header>
        <h2>Header</h2>
    </header>
    <main>
        {{ $slot }}
    </main>
        <footer>
            <h2>Footer</h2>
        </footer>
</body>

</html>