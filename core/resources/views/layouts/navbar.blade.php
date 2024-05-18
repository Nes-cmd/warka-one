@php
$currentRoute = Route::current()->getName();
@endphp
<nav class="container m-auto flex justify-between items-center py-4 bg-transparent px-3">
    <a class="w-36" href="/">
        <x-application-logo />
    </a>
    <ul class="sm:flex items-center gap-3 hidden">

        <x-nav-link href="/">
            Home
        </x-nav-link>
        <x-nav-link href="/services">
            Services
        </x-nav-link>
        <x-nav-link href="/about">
            About
        </x-nav-link>
        <x-nav-link href="/contact">
            Contact us
        </x-nav-link>
    </ul>
    <button onclick="toggleNavbar(true)" class="sm:hidden">
        <svg width="24" height="25" class="dark:stroke-gray-50 stroke-gray-800" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 10.5H7C9 10.5 10 9.5 10 7.5V5.5C10 3.5 9 2.5 7 2.5H5C3 2.5 2 3.5 2 5.5V7.5C2 9.5 3 10.5 5 10.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M17 10.5H19C21 10.5 22 9.5 22 7.5V5.5C22 3.5 21 2.5 19 2.5H17C15 2.5 14 3.5 14 5.5V7.5C14 9.5 15 10.5 17 10.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M17 22.5H19C21 22.5 22 21.5 22 19.5V17.5C22 15.5 21 14.5 19 14.5H17C15 14.5 14 15.5 14 17.5V19.5C14 21.5 15 22.5 17 22.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M5 22.5H7C9 22.5 10 21.5 10 19.5V17.5C10 15.5 9 14.5 7 14.5H5C3 14.5 2 15.5 2 17.5V19.5C2 21.5 3 22.5 5 22.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>


    <ul class="sm:flex items-center gap-3 hidden">
        <div class="">
            <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400  focus:outline-none   text-sm p-2 mr-3">
                <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        @auth
        <!-- notification  -->
        <x-nav-link>
            <svg width="24" height="25" class="dark:stroke-gray-50 stroke-gray-800" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 6.93994V10.2699" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                <path d="M12.0199 2.5C8.3399 2.5 5.3599 5.48 5.3599 9.16V11.26C5.3599 11.94 5.0799 12.96 4.7299 13.54L3.4599 15.66C2.6799 16.97 3.2199 18.43 4.6599 18.91C9.4399 20.5 14.6099 20.5 19.3899 18.91C20.7399 18.46 21.3199 16.88 20.5899 15.66L19.3199 13.54C18.9699 12.96 18.6899 11.93 18.6899 11.26V9.16C18.6799 5.5 15.6799 2.5 12.0199 2.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                <path d="M15.3299 19.3199C15.3299 21.1499 13.8299 22.6499 11.9999 22.6499C11.0899 22.6499 10.2499 22.2699 9.64992 21.6699C9.04992 21.0699 8.66992 20.2299 8.66992 19.3199" stroke-width="1.5" stroke-miterlimit="10" />
            </svg>
        </x-nav-link>
        <!-- profile  -->
        <div class="border border-gray-600 p-1 rounded-full flex gap-3 items-center">
            <svg width="24" height="24" viewBox="0 0 24 24" class="stroke-gray-300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.12 12.78C12.05 12.77 11.96 12.77 11.88 12.78C10.12 12.72 8.71997 11.28 8.71997 9.50998C8.71997 7.69998 10.18 6.22998 12 6.22998C13.81 6.22998 15.28 7.69998 15.28 9.50998C15.27 11.28 13.88 12.72 12.12 12.78Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M18.74 19.3801C16.96 21.0101 14.6 22.0001 12 22.0001C9.40001 22.0001 7.04001 21.0101 5.26001 19.3801C5.36001 18.4401 5.96001 17.5201 7.03001 16.8001C9.77001 14.9801 14.25 14.9801 16.97 16.8001C18.04 17.5201 18.64 18.4401 18.74 19.3801Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <svg width="16" height="16" viewBox="0 0 24 24" class="stroke-gray-300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.9201 8.94995L13.4001 15.47C12.6301 16.24 11.3701 16.24 10.6001 15.47L4.08008 8.94995" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </div>
        @endauth

        @guest
        <x-nav-link :href="route('login')">
            <div class="border border-secondary px-6 w-full py-2 flex-shrink-0 rounded-full flex justify-center items-center dark:hover:bg-gray-600 ">
                My Account
            </div>
        </x-nav-link>

        @endguest

    </ul>

</nav>

<div class="flex items-center container m-auto">
    <div class="flex-grow bg-gray-500 h-px"></div>
    <!-- <div class="text-gray-500 mx-4">Page Divider</div> -->
    <div class="flex-grow bg-gray-600 h-px"></div>
</div>

<section id="navbar" class="hidden shadow-sm w-2/3 rounded-lg dark:bg-[#1E293B] bg-white py-10 px-4  absolute top-0 left-0 flex flex-col items-center gap-7 ease-in-out duration-300 delay-200 transition-all translate-animate">
    <div onclick="toggleNavbar(false)" class="absolute top-4 left-4">
        <svg width="24" height="24" class="dark:fill-gray-300 fill-gray-800" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.46967 5.46967C5.76256 5.17678 6.23744 5.17678 6.53033 5.46967L12 10.9393L17.4697 5.46967C17.7626 5.17678 18.2374 5.17678 18.5303 5.46967C18.8232 5.76256 18.8232 6.23744 18.5303 6.53033L13.0607 12L18.5303 17.4697C18.8232 17.7626 18.8232 18.2374 18.5303 18.5303C18.2374 18.8232 17.7626 18.8232 17.4697 18.5303L12 13.0607L6.53033 18.5303C6.23744 18.8232 5.76256 18.8232 5.46967 18.5303C5.17678 18.2374 5.17678 17.7626 5.46967 17.4697L10.9393 12L5.46967 6.53033C5.17678 6.23744 5.17678 5.76256 5.46967 5.46967Z" />
        </svg>
    </div>
    <a href="{{ url('/') }}" class="w-36">
        <x-application-logo />
    </a>
    <x-nav-link href="/">
        Home
    </x-nav-link>
    <x-nav-link href="/services">
        Services
    </x-nav-link>
    <x-nav-link href="/about">
        About
    </x-nav-link>
    <x-nav-link href="/contact">
        Contact us
    </x-nav-link>
    <x-nav-link>
        Help
    </x-nav-link>
    @auth
    <!-- notification  -->
    <x-nav-link>
        <svg width="24" height="25" class="dark:stroke-gray-50 stroke-gray-800" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 6.93994V10.2699" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
            <path d="M12.0199 2.5C8.3399 2.5 5.3599 5.48 5.3599 9.16V11.26C5.3599 11.94 5.0799 12.96 4.7299 13.54L3.4599 15.66C2.6799 16.97 3.2199 18.43 4.6599 18.91C9.4399 20.5 14.6099 20.5 19.3899 18.91C20.7399 18.46 21.3199 16.88 20.5899 15.66L19.3199 13.54C18.9699 12.96 18.6899 11.93 18.6899 11.26V9.16C18.6799 5.5 15.6799 2.5 12.0199 2.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
            <path d="M15.3299 19.3199C15.3299 21.1499 13.8299 22.6499 11.9999 22.6499C11.0899 22.6499 10.2499 22.2699 9.64992 21.6699C9.04992 21.0699 8.66992 20.2299 8.66992 19.3199" stroke-width="1.5" stroke-miterlimit="10" />
        </svg>
    </x-nav-link>
    <!-- profile  -->
    <div class="border border-gray-600 p-1 rounded-full flex gap-3 items-center">
        <svg width="24" height="24" viewBox="0 0 24 24" class="stroke-gray-300" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.12 12.78C12.05 12.77 11.96 12.77 11.88 12.78C10.12 12.72 8.71997 11.28 8.71997 9.50998C8.71997 7.69998 10.18 6.22998 12 6.22998C13.81 6.22998 15.28 7.69998 15.28 9.50998C15.27 11.28 13.88 12.72 12.12 12.78Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M18.74 19.3801C16.96 21.0101 14.6 22.0001 12 22.0001C9.40001 22.0001 7.04001 21.0101 5.26001 19.3801C5.36001 18.4401 5.96001 17.5201 7.03001 16.8001C9.77001 14.9801 14.25 14.9801 16.97 16.8001C18.04 17.5201 18.64 18.4401 18.74 19.3801Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <svg width="16" height="16" viewBox="0 0 24 24" class="stroke-gray-300" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.9201 8.94995L13.4001 15.47C12.6301 16.24 11.3701 16.24 10.6001 15.47L4.08008 8.94995" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>
    @endauth

    @guest

    <x-nav-link :href="route('login')" class="w-full">
        <div class="border border-gray-600 w-full py-3 flex-shrink-0 rounded-full flex justify-center items-center hover:dark:bg-gray-600 ">
            Login
        </div>
    </x-nav-link>

    @endguest


</section>
<script>
    function toggleNavbar(open) {
        var navbar = document.getElementById("navbar");
        var openIcon = document.getElementById("openIcon");
        var closeIcon = document.getElementById("closeIcon");


        if (open) {
            navbar.classList.remove("hidden");
            openIcon.classList.add("hidden");
            closeIcon.classList.remove("hidden");
        } else {
            navbar.classList.add("hidden");
            openIcon.classList.remove("hidden");
            closeIcon.classList.add("hidden");
        }
    }
</script>