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

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') == 'dark' ) {
            console.log('dark');
            document.documentElement.classList.add('dark');
        } else {
            console.log('light');
            document.documentElement.classList.remove('dark')
        }
    </script>

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

        /* Background Elements */
        .moon {
            position: fixed;
            top: 10%;
            right: 10%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #ffffff, #f8fafc, #e2e8f0);
            border-radius: 50%;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.6);
            opacity: 0.9;
            z-index: -1;
            animation: moonGlow 4s ease-in-out infinite alternate;
        }

        .shooting-star {
            position: fixed;
            width: 3px;
            height: 3px;
            background: radial-gradient(circle, #ffffff 0%, #fbbf24 30%, #60a5fa 70%, transparent 100%);
            border-radius: 50%;
            box-shadow: 
                0 0 6px rgba(255, 255, 255, 0.8),
                0 0 12px rgba(251, 191, 36, 0.6),
                0 0 20px rgba(96, 165, 250, 0.4),
                inset 0 0 2px rgba(255, 255, 255, 0.9);
            z-index: -1;
            animation: shootingStar1 20s linear infinite, twinkle 2s ease-in-out infinite;
        }

        .shooting-star:nth-child(2) {
            top: 15%;
            left: 85%;
            animation: shootingStar2 25s linear infinite, twinkle 1.5s ease-in-out infinite;
            animation-delay: 5s, 0s;
        }

        .shooting-star:nth-child(3) {
            top: 45%;
            left: 15%;
            animation: shootingStar3 18s linear infinite, twinkle 2.5s ease-in-out infinite;
            animation-delay: 10s, 1s;
        }

        .shooting-star:nth-child(4) {
            top: 25%;
            left: 75%;
            animation: shootingStar4 30s linear infinite, twinkle 1.8s ease-in-out infinite;
            animation-delay: 15s, 2s;
        }

        .shooting-star:nth-child(5) {
            top: 65%;
            left: 25%;
            animation: shootingStar5 22s linear infinite, twinkle 2.2s ease-in-out infinite;
            animation-delay: 20s, 3s;
        }

        .shooting-star:nth-child(6) {
            top: 35%;
            left: 65%;
            animation: shootingStar6 28s linear infinite, twinkle 1.6s ease-in-out infinite;
            animation-delay: 25s, 4s;
        }

        .shooting-star::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 120px;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.8) 20%, 
                rgba(251, 191, 36, 0.6) 40%, 
                rgba(96, 165, 250, 0.4) 60%, 
                transparent 100%);
            transform: translate(-50%, -50%) rotate(45deg);
            border-radius: 50%;
        }

        .shooting-star::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80px;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.6) 30%, 
                rgba(251, 191, 36, 0.4) 60%, 
                transparent 100%);
            transform: translate(-50%, -50%) rotate(-45deg);
            border-radius: 50%;
        }

        @keyframes twinkle {
            0%, 100% {
                opacity: 0.3;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        @keyframes moonGlow {
            0% {
                box-shadow: 0 0 30px rgba(255, 255, 255, 0.6);
                transform: scale(1);
            }
            100% {
                box-shadow: 0 0 50px rgba(255, 255, 255, 0.8);
                transform: scale(1.05);
            }
        }

        @keyframes shootingStar1 {
            0% {
                transform: translateX(-150px) translateY(0);
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                transform: translateX(calc(100vw + 150px)) translateY(-100px);
                opacity: 0;
            }
        }

        @keyframes shootingStar2 {
            0% {
                transform: translateX(calc(100vw + 150px)) translateY(0);
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                transform: translateX(-150px) translateY(100px);
                opacity: 0;
            }
        }

        @keyframes shootingStar3 {
            0% {
                transform: translateX(-150px) translateY(0);
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                transform: translateX(calc(100vw + 150px)) translateY(-150px);
                opacity: 0;
            }
        }

        @keyframes shootingStar4 {
            0% {
                transform: translateX(calc(100vw + 150px)) translateY(0);
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                transform: translateX(-150px) translateY(120px);
                opacity: 0;
            }
        }

        @keyframes shootingStar5 {
            0% {
                transform: translateX(-150px) translateY(0);
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                transform: translateX(calc(100vw + 150px)) translateY(-80px);
                opacity: 0;
            }
        }

        @keyframes shootingStar6 {
            0% {
                transform: translateX(calc(100vw + 150px)) translateY(0);
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% {
                transform: translateX(-150px) translateY(90px);
                opacity: 0;
            }
        }

        /* Dark mode specific enhancements */
        .dark .moon {
            background: linear-gradient(45deg, #ffffff, #f1f5f9, #e2e8f0);
            box-shadow: 0 0 40px rgba(255, 255, 255, 0.8);
        }

        .dark .shooting-star {
            background: radial-gradient(circle, #ffffff 0%, #fbbf24 20%, #60a5fa 60%, #3b82f6 80%, transparent 100%);
            box-shadow: 
                0 0 8px rgba(255, 255, 255, 1),
                0 0 16px rgba(251, 191, 36, 0.8),
                0 0 24px rgba(96, 165, 250, 0.6),
                0 0 32px rgba(59, 130, 246, 0.4),
                inset 0 0 3px rgba(255, 255, 255, 1);
        }

        .dark .shooting-star::before {
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 1) 20%, 
                rgba(251, 191, 36, 0.8) 40%, 
                rgba(96, 165, 250, 0.6) 60%, 
                rgba(59, 130, 246, 0.4) 80%, 
                transparent 100%);
        }

        .dark .shooting-star::after {
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.8) 30%, 
                rgba(251, 191, 36, 0.6) 60%, 
                rgba(96, 165, 250, 0.4) 80%, 
                transparent 100%);
        }

        /* Floating particles for dark mode */
        .dark .floating-particle {
            position: fixed;
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            z-index: -1;
            animation: float 8s ease-in-out infinite;
        }

        .floating-particle:nth-child(1) { top: 10%; left: 20%; animation-delay: 0s; }
        .floating-particle:nth-child(2) { top: 20%; left: 80%; animation-delay: 2s; }
        .floating-particle:nth-child(3) { top: 60%; left: 10%; animation-delay: 4s; }
        .floating-particle:nth-child(4) { top: 80%; left: 70%; animation-delay: 6s; }
        .floating-particle:nth-child(5) { top: 40%; left: 90%; animation-delay: 8s; }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 0.8;
            }
        }
    </style>
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