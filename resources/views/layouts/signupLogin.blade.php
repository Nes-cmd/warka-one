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
    <style>
        .loader {
            border: 6px solid #f3f3f3;
            /* Light grey */
            border-top: 6px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased h-screen overflow-hidden justify-center items-center flex">
    <!-- <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
        </div>
    </div> -->

    <div class="flex lg:flex-row flex-col items-center justify-center   w-[90%] h-[90%]">
        <div class="w-1/2 h-full  bg-primary-50 flex  items-center  px-10">

            <img src="{{ asset('assets/kerbrands/loginAvatar.svg')}}" class="md:block hidden" alt="loginAvatar" />
        </div>
        <div class="w-1/2 flex flex-col gap-6 items-center justify-center border h-full">
            <div class="w-[75%] flex flex-col gap-8">
                <!-- logo  -->
                <div class=" ">
                    <a href="{{ url()->previos() }}" class="">
                        <x-application-logo class="  fill-current text-gray-500" />
                    </a>
                </div>

                <div >
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

</body>

</html>