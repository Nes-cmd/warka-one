<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link nonce="{{ csp_nonce() }}" rel="preconnect" href="https://fonts.bunny.net">
    <link nonce="{{ csp_nonce() }}" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="icon" href="{{ asset('assets/kerbrands/ker-min.png')}}">

    <link nonce="{{ csp_nonce() }}" rel="stylesheet" href="{{ asset('css/custom-ctyle.css') }}" >

    <!-- <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') == 'dark' ) {
            console.log('dark');
            document.documentElement.classList.add('dark');
        } else {
            console.log('light');
            document.documentElement.classList.remove('dark')
        }
    </script> -->

    <!-- Scripts -->
    @livewireStyles
    @livewireScripts
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 dark:bg-[#0F172A] bg-[#EAE9F0] antialiased h-screen overflow-hidden justify-center items-center flex">

    <!-- Background Elements -->
    <div class="moon"></div>
    <div class="shooting-star"></div>
    <div class="shooting-star"></div>
    <div class="shooting-star"></div>
    <div class="shooting-star"></div>
    <div class="shooting-star"></div>
    
    <!-- Floating particles for dark mode -->
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>

    <div class="flex lg:flex-row flex-col items-center justify-center px-2 w-[100%] md:w-[90%] h-[85%]">
        
        <div class="lg:w-1/2 w-full flex flex-col gap-6 bg-primary-50 dark:bg-[#1f2533] rounded-lg md:rounded-none items-center justify-center h-full">
            <div class="md:w-[75%] w-[90%] flex flex-col p gap-4 ">
                

                <div class="pb-4 relative ">
                    {{ $slot }}
                     <div class="absolute md:hidden -top-20 left-1/3 w-52 h-[100px] rotate-30 bg-gradient-to-t from-primary-300/40 to-primary-100/40  blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ csp_nonce() }}" src="{{ asset('js/custom-script.js') }}"></script>


</body>

</html>