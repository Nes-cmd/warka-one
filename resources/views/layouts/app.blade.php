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

    <script nonce="{{ csp_nonce() }}">
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') == 'dark') {
            document.documentElement.classList.add('dark');
            console.log('dark');
        } else {
            console.log('light');
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="font-sans antialiased bg-white dark:bg-[#0F172A]">
    <div class="min-h-screen bg-white dark:bg-[#0F172A]">
        @include('layouts.navigation')

        <!-- Page Heading -->   
        @if (isset($header))
        <header class="">
            <div class="max-w-7xl mx-auto">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="container mx-auto lg:px-24 md:px-12 px-4">
            {{ $slot }}
        </main>
    </div>
</body>

</html>