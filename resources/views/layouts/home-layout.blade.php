<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Primary Meta Tags -->
    <meta name="description" content="Secure single sign-on authentication system for seamless access to all our services">
    <meta name="keywords" content="SSO, authentication, login, secure access">
    <meta name="author" content="Your Company Name">
    
    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }} - Secure Single Sign-On">
    <meta property="og:description" content="Secure single sign-on authentication system for seamless access to all our services">
    <meta property="og:image" content="{{ asset('assets/kerbrands/ker-min.png') }}">
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ config('app.name', 'Laravel') }} - Secure Single Sign-On">
    <meta name="twitter:description" content="Secure single sign-on authentication system for seamless access to all our services">
    <meta name="twitter:image" content="{{ asset('assets/kerbrands/ker-min.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.js"></script>

    <link rel="icon" href="{{ asset('assets/kerbrands/ker-min.png')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
    <!-- Scripts -->
    @livewireStyles
    @livewireScripts

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script nonce="{{ csp_nonce() }}">
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

</head>

<body class="font-sans text-gray-900 dark:bg-[#0F172A] bg-[#EAE9F0] justify-center items-center flex-column">

    <header class="lg:px-24 md:px-12 px-4">
        @include('layouts.navbar')
    </header>
    <main class="lg:px-24 md:px-12 px-4">
        {{ $slot }}
    </main>
    <footer class="lg:px-24 md:px-12 px-4">
        @include('layouts.footer')
    </footer>

    <script nonce="{{ csp_nonce() }}">
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {

            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }

                // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }

        });
    </script>
</body>

</html>