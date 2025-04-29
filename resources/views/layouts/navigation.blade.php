<nav x-data="{ open: false }" class="bg-white lg:px-24 md:px-12  dark:bg-[#0F172A]  border-b border-primary-200 dark:border-slate-700">
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto px-6">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block  w-auto fill-current text-gray-800" />
                    </a>
                </div>
                <div class="flex space-x-4 pl-4 mt-4">
                    <div  class="{{ Route::is('account')?'border-b-primary dark:border-b-secondary border-b-2':'' }}  dark:text-white"><a href="{{route('account')}}">My Account</a></div> 
                    <div class="{{ Route::is('profile.update-profile')?'border-b-primary dark:border-b-secondary border-b-2':'' }}  dark:text-white"><a href="{{route('profile.update-profile')}}">Edit Profile</a></div> 
                    <div class="{{ Route::is('clients.index')?'border-b-primary dark:border-b-secondary border-b-2':'' }}  dark:text-white"><a href="{{route('clients.index')}}">My Apps</a></div>
                </div>
            </div>
           
            <!-- Settings Dropdown -->
            <div class="flex items-center ">
                <div>

                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400  focus:outline-none   text-sm p-2 lg:mr-3">

                        <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center      text-sm leading-4 font-medium rounded-full  hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div id="initial" class="3xl:w-[41px]  w-[32px] aspect-square text-xl text-primary flex items-center justify-center font-bold rounded-full bg-primary-100">

                            </div>

                                <!-- <div class="ms-1">
                                    <svg class="fill-current h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div> -->
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('account')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

           
        </div>
    </div>
    
</nav>

<script>
    // Get the user's name
    var username = "{{ Auth::user()->name }}";

    // Extract the first letter
    var firstLetter = username.charAt(0).toUpperCase();

    // Replace the "B" with the first letter
    document.getElementById("initial").textContent = firstLetter;


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