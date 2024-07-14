<x-home-layout>
    <main>
        <section class="max-w-5xl mx-auto  flex flex-col  justify-between relative   ">
            <div class="lg:pt-32 pt-24 relative md:px-4">
                <h1 class="text-center dark:text-white text-slate-900 md:text-5xl md:font-semibold text-3xl  capitalize">
                    we want to help you make your business and your life easier
                </h1>
                <div class="absolute top-0 left-10  md:left-1/2 w-32 md:w-52 h-48 md:h-96 rotate-90   bg-primary-500/30 blur-3xl"></div>
                <p class="dark:text-gray-500 text-slate-900  lg:text-xl my-7 text-center px-10">
                Welcome to Ker Labs, where we revolutionize the way you manage your digital world. Imagine having one account to rule them all—a centralized hub that simplifies your online experience and brings together a multitude of solutions. With our Single Sign-On (SSO) technology, we provide a seamless and efficient way to access and manage all your digital resources from a single personality.
                </p>
            </div>

        </section>
        <section class="max-w-9xl mx-auto">
            <div class="w-full relative flex md:flex-row flex-col items-center justify-center  mt-10">
                <div class="md:w-1/2">
                    <img class=" dark:invert" src=" {{ asset('assets/image/about.png') }}" alt="" srcset="">
                </div>
                <div class="md:w-1/3 flex flex-col gap-3 items-center md:items-start px-4">
                    <h1 class="dark:text-white text-slate-900  md:font-semibold text-3xl md:text-start text-center  capitalize">
                        What We do?
                    </h1>
                    <p class="dark:text-gray-500 text-slate-900 md:text-start text-center my-4">
                    Our digital hub is designed to streamline your online activities, saving you valuable time and energy. No more wasting precious moments searching for passwords or navigating through countless login screens. With Ker Labs, you can focus on what truly matters—your work, your connections, and your passions.
                    </p>
                    <div class="">
                        <a class="dark:text-white/70 text-slate-900 px-4 py-2 my-7 rounded-full border border-slate-900 dark:border-white/40 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-default">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="absolute top-0  md:left-1/2 left-0   w-16 md:w-48 h-full rotate-90 bg-gradient-to-tr from-primary-600/40 via-secondary-900/40 to-primary-800/40 blur-3xl"></div>

            </div>
        </section>
        
        <section class="max-w-7xl mx-auto my-10 relative">
            <div class="flex flex-col items-center">
                <img src="{{ asset('assets/image/Group.svg') }}" alt="" srcset="" class="dark:invert w-[30%] sm:w-[20%] md:w-[15%] lg:w-[10%]">
                <h1 class="text-center dark:text-white text-slate-900 text-3xl md:font-semibold   capitalize">
                    Meet the Wizards Behind the Curtain
                </h1>
            </div>
            <div class="w-full grid md:grid-cols-3 grid-cols-2 gap-1 md:gap-2 lg:gap-10 mt-20 ">
                <div class=" group relative rounded-xl col-span-1 flex flex-col items-center gap-3 dark:bg-[#1E293B] bg-white py-4 hover:-translate-y-3 border border-transparent ease-in-out duration-300 transition-all cursor-pointer ">
                    <div class="lg:w-20 lg:h-20 w-16 h-16 rounded-full absolute -top-10 z-10 dark:bg-[#1E293B] bg-gray-200">
                        <img src="{{ asset('assets/image/Preview.svg') }}" alt="" srcset="" class="dark:invert-0 ">
                    </div>
                    <p class=" md:text-sm mt-10 lg:text-md text-slate-600 dark:text-slate-400 text-center px-2">Lorem ipsum dolor sit amet consectetur, adipisicing elit illo.</p>
                    <div class="text-gray-400 text-sm  font-semibold group-hover:text-secondary mt-5">
                        Lorem ipsum dolor sit
                    </div>
                </div>
                <div class=" group relative rounded-xl col-span-1 flex flex-col items-center gap-3 dark:bg-[#1E293B] bg-white py-4 hover:-translate-y-3 border border-transparent ease-in-out duration-300 transition-all cursor-pointer ">
                    <div class="lg:w-20 lg:h-20 w-16 h-16 rounded-full absolute -top-10 dark:bg-[#1E293B] bg-gray-200">
                        <img src="{{ asset('assets/image/Preview.svg') }}" alt="" srcset="" class="dark:invert-0 ">
                    </div>
                    <p class=" md:text-sm mt-10 lg:text-md text-slate-600 dark:text-slate-400  text-center ">Lorem ipsum dolor sit amet consectetur, adipisicing elit. </p>
                    <div class="text-gray-400 text-sm  font-semibold group-hover:text-secondary mt-5">
                        Lorem ipsum dolor sit
                    </div>
                </div>
                <div class=" md:mt-0 mt-10 group relative rounded-xl col-span-1 flex flex-col items-center gap-3 dark:bg-[#1E293B] bg-white py-4 hover:-translate-y-3 border border-transparent ease-in-out duration-300 transition-all cursor-pointer ">
                    <div class="lg:w-20 lg:h-20 w-16 h-16 rounded-full absolute -top-10 dark:bg-[#1E293B] bg-gray-200">
                        <img src="{{ asset('assets/image/Preview.svg') }}" alt="" srcset="" class="dark:invert-0 ">
                    </div>
                    <p class=" md:text-sm mt-10 lg:text-md text-slate-600 dark:text-slate-400 text-center px-2">Lorem ipsum dolor sit amet consequuntur ratione ullam illo.</p>
                    <div class="text-gray-400 text-sm  font-semibold group-hover:text-secondary mt-5">
                        Lorem ipsum dolor sit
                    </div>
                </div>

            </div>
            <div class="absolute top-0 left-0  md:left-1/2  w-16 md:w-48 h-full rotate-90 bg-gradient-to-tr from-primary-400/40 via-secondary-900/40 to-primary-800/40 blur-3xl"></div>

        </section>
    </main>
</x-home-layout>