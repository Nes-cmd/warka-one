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

<body class="font-sans text-gray-900  antialiased h-screen overflow-hidden justify-center items-center flex">

    <div class="flex lg:flex-row flex-col items-center justify-center   w-[100%] md:w-[90%] h-[85%]">
        <div class="w-1/2 h-full border bg-primary-50 flex lg:block items-center  px-10">
            <img src="{{ asset('assets/kerbrands/loginAvatar.svg')}}" class=" h-full" alt="Ker Logo" />
        </div>
        <div class="lg:w-1/2 w-full flex flex-col gap-6 items-center justify-center h-full">
            <div class="md:w-[75%] w-[90%] flex flex-col  gap-4 ">
                <!-- logo  -->
                <div class="h-14 lg:h-12 w-full flex justify-center lg:justify-start ">
                    <a href="/" class="h-full ">
                        <x-application-logo class=" w-full h-full fill-current text-gray-500" />
                    </a>
                </div>

                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('passwordInput');
            var togglePasswordIcon = document.getElementById('togglePasswordIcon');
            var showIcon = document.getElementById('showIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showIcon.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                showIcon.style.display = 'none';
            }
        }
    </script>


</body>

</html>