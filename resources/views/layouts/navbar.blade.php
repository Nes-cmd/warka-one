<nav class="flex justify-between items-center py-4 bg-transparent">
    <a class="" href="/"><x-application-logo /></a>
    
    <!-- Desktop Navigation -->
    <ul class="sm:flex items-center gap-3 hidden">
        <x-nav-link href="/" :active="request()->is('/')">
            Home
        </x-nav-link>
        <x-nav-link href="/services" :active="request()->is('services')">
            About
        </x-nav-link>
        <!-- <x-nav-link href="/about" :active="request()->is('about')">
            About
        </x-nav-link> -->
        <x-nav-link href="/contact" :active="request()->is('contact')">
            Contact us
        </x-nav-link>
    </ul>
    
    <div class="flex items-center gap-3">
        <!-- Mobile Menu Button -->
        <button onclick="toggleNavbar(true)" class="sm:hidden">
            <svg width="24" height="25" class="dark:stroke-gray-50 stroke-gray-800" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 10.5H7C9 10.5 10 9.5 10 7.5V5.5C10 3.5 9 2.5 7 2.5H5C3 2.5 2 3.5 2 5.5V7.5C2 9.5 3 10.5 5 10.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17 10.5H19C21 10.5 22 9.5 22 7.5V5.5C22 3.5 21 2.5 19 2.5H17C15 2.5 14 3.5 14 5.5V7.5C14 9.5 15 10.5 17 10.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17 22.5H19C21 22.5 22 21.5 22 19.5V17.5C22 15.5 21 14.5 19 14.5H17C15 14.5 14 15.5 14 17.5V19.5C14 21.5 15 22.5 17 22.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5 22.5H7C9 22.5 10 21.5 10 19.5V17.5C10 15.5 9 14.5 7 14.5H5C3 14.5 2 15.5 2 17.5V19.5C2 21.5 3 22.5 5 22.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    <!-- Desktop Auth Section with Dark Mode Toggle -->
    <div class="sm:flex items-center gap-4 hidden">
        <!-- Dark Mode Toggle moved here -->
        <button id="theme-toggle-desktop" type="button" class="text-gray-500 dark:text-gray-400 focus:outline-none text-sm p-2">
            <svg id="theme-toggle-desktop-dark-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg id="theme-toggle-desktop-light-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </button>

        @auth
            <!-- User Account Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 border border-gray-600 p-1 px-3 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-lg font-bold text-primary dark:text-secondary">{{ substr(auth()->user()->name ?? auth()->user()->email, 0, 1) }}</span>
                        @endif
                    </div>
                    <span class="text-sm font-medium dark:text-gray-200">{{ Str::limit(auth()->user()->name, 12) }}</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" class="stroke-gray-500 dark:stroke-gray-300" fill="none" xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': open}">
                        <path d="M19.9201 8.94995L13.4001 15.47C12.6301 16.24 11.3701 16.24 10.6001 15.47L4.08008 8.94995" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    
                    <a href="{{ route('account') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('account') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        My Account
                    </a>
                    <a href="{{ route('clients.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('clients.*') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        My Applications
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex items-center gap-3">
                <x-nav-link :href="route('login')" :active="request()->routeIs('login')" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    Login
                </x-nav-link>
                <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    <div class="border border-primary bg-primary text-white px-4 py-1.5 rounded-lg hover:bg-primary-600 transition-colors">
                        Register
                    </div>
                </x-nav-link>
            </div>
        @endauth
    </div>
</nav>

<!-- divider line -->
<div class="flex items-center container m-auto">
    <div class="flex-grow bg-gray-500 h-px"></div>
    <div class="flex-grow bg-gray-600 h-px"></div>
</div>

<!-- Mobile Navigation Menu -->
<section id="navbar" class="hidden shadow-sm w-full sm:w-2/3 rounded-lg dark:bg-slate-800 bg-white py-2 pb-6 px-4 absolute top-0 right-0 left-0 z-50 flex flex-col gap-7 ease-in-out duration-300 transition-all">
    <div class="flex justify-between items-center">
        <a href="{{ url('/') }}" class="w-36">
            <x-application-logo />
        </a>
        <div onclick="toggleNavbar(false)" class="cursor-pointer">
            <svg width="24" height="24" class="dark:fill-gray-300 fill-gray-800" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.46967 5.46967C5.76256 5.17678 6.23744 5.17678 6.53033 5.46967L12 10.9393L17.4697 5.46967C17.7626 5.17678 18.2374 5.17678 18.5303 5.46967C18.8232 5.76256 18.8232 6.23744 18.5303 6.53033L13.0607 12L18.5303 17.4697C18.8232 17.7626 18.8232 18.2374 18.5303 18.5303C18.2374 18.8232 17.7626 18.8232 17.4697 18.5303L12 13.0607L6.53033 18.5303C6.23744 18.8232 5.76256 18.8232 5.46967 18.5303C5.17678 18.2374 5.17678 17.7626 5.46967 17.4697L10.9393 12L5.46967 6.53033C5.17678 6.23744 5.17678 5.76256 5.46967 5.46967Z" />
            </svg>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <x-nav-link href="/" :active="request()->is('/')" class="w-full">
            Home
        </x-nav-link>
        <x-nav-link href="/services" :active="request()->is('services')" class="w-full">
            About
        </x-nav-link>
        <!-- <x-nav-link href="/about" :active="request()->is('about')" class="w-full">
            About
        </x-nav-link> -->
        <x-nav-link href="/contact" :active="request()->is('contact')" class="w-full">
            Contact us
        </x-nav-link>
    </div>

    <!-- Mobile Auth Links -->
    <div class="mt-4">
        <!-- Add Dark Mode Toggle to mobile menu -->
        <div class="flex justify-end mb-4">
            <button id="mobile-theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 focus:outline-none text-sm p-2">
                <svg id="mobile-theme-toggle-dark-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="mobile-theme-toggle-light-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        @auth
            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-lg font-bold text-primary dark:text-secondary">{{ substr(auth()->user()->name ?? auth()->user()->email, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="font-medium dark:text-white">{{ auth()->user()->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <a href="{{ route('account') }}" class="block w-full py-2 px-3 text-left rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 transition {{ request()->routeIs('account') ? 'bg-gray-200 dark:bg-gray-600 font-medium' : '' }}">
                        My Account
                    </a>
                    <a href="{{ route('clients.index') }}" class="block w-full py-2 px-3 text-left rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 transition {{ request()->routeIs('clients.*') ? 'bg-gray-200 dark:bg-gray-600 font-medium' : '' }}">
                        My Applications
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full py-2 px-3 text-left rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 text-red-600 dark:text-red-400 transition">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}" class="block w-full py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-center dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition {{ request()->routeIs('login') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block w-full py-3 rounded-lg bg-primary border border-primary text-white text-center hover:bg-primary-600 transition {{ request()->routeIs('register') ? 'font-medium' : '' }}">
                    Register
                </a>
            </div>
        @endauth
    </div>
</section>

<script>
    function toggleNavbar(open) {
        var navbar = document.getElementById("navbar");
        
        if (open) {
            navbar.classList.remove("hidden");
            document.body.style.overflow = "hidden"; // Prevent scrolling when menu is open
        } else {
            navbar.classList.add("hidden");
            document.body.style.overflow = ""; // Restore scrolling
        }
    }
    
    // Make sure both theme toggles work
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggles = ['theme-toggle-desktop', 'mobile-theme-toggle'];
        
        themeToggles.forEach(function(toggleId) {
            const toggle = document.getElementById(toggleId);
            if (toggle) {
                toggle.addEventListener('click', function() {
                    // Toggle theme logic
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.theme = 'light';
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.theme = 'dark';
                    }
                    
                    // Update both toggle button icons
                    updateThemeIcons();
                });
            }
        });
        
        // Initial theme icon setup
        updateThemeIcons();
        
        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            
            themeToggles.forEach(function(toggleId) {
                const darkIcon = document.getElementById(toggleId + '-dark-icon');
                const lightIcon = document.getElementById(toggleId + '-light-icon');
                
                if (darkIcon && lightIcon) {
                    if (isDark) {
                        darkIcon.classList.add('hidden');
                        lightIcon.classList.remove('hidden');
                    } else {
                        darkIcon.classList.remove('hidden');
                        lightIcon.classList.add('hidden');
                    }
                }
            });
        }
    });
</script>