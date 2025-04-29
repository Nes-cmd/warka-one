<x-home-layout>

    <main class="max-w-7xl mx-auto">
        <div class="absolute top-0 flex justify-end w-32 md:w-52 h-96 md:translate-x-1/2 rotate-45 dark:bg-primary/40 bg-primary-500/10 blur-3xl rounded-full"></div>
        <!-- <div class="absolute  bottom-0   flex justify-end w-52 h-96 md:translate-x-1/2 right-0 rotate-45 dark:bg-secondary-900/30 bg-secondary-500/10 blur-3xl rounded-full"></div> -->

        <div class="max-w-2xl mx-auto my-10">
            <p class="text-slate-900 text-4xl text-center tracking-tight font-extrabold sm:text-5xl dark:text-white">
                One Account, Endless Possibilities
            </p>
        </div>
        <section class="lg:mx-20 md:mx-10 mx-4 my-10 flex flex-col gap-20 items-start">
            <!-- Single Sign-On (SSO) Section -->
            <div class="flex flex-col gap-8 max-w-4xl">
                <div>
                    <div class="w-16 md:w-20 lg:w-24 xl:w-28 aspect-square rounded-full border dark:border-indigo-400 border-secondary-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-1/2 h-1/2 text-secondary-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <p class="font-semibold my-2 text-secondary-600 dark:text-indigo-400">
                        Single Sign-On
                    </p>
                </div>
                <h1 class="dark:text-white/70 text-slate-900 text-3xl font-bold">
                    One Login, Unlimited Access
                </h1>
                <p class="dark:text-gray-500 text-slate-900 lg:text-xl">
                    With our Single Sign-On (SSO) solution, users can access multiple applications with just one set of credentials. No more password juggling or multiple logins. Our system provides seamless authentication across all connected services, enhancing security while dramatically improving user experience. Implementation is simple for developers, with our comprehensive API and SDKs for major platforms.
                </p>
                <div>
                    <a href="{{ route('register') }}" class="dark:text-white/70 text-slate-900 px-4 py-2 rounded-full border border-slate-900 dark:border-white/40 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Get Started
                    </a>
                </div>
            </div>

            <!-- Authentication Options Section -->
            <div class="flex flex-col gap-8 self-end max-w-4xl">
                <div>
                    <div class="w-16 md:w-20 lg:w-24 xl:w-28 aspect-square rounded-full border border-indigo-400 dark:border-secondary-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-1/2 h-1/2 text-indigo-400 dark:text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="font-semibold my-2 dark:text-secondary-600 text-indigo-400">
                        Flexible Authentication
                    </p>
                </div>
                <h1 class="dark:text-white/70 text-slate-900 text-3xl font-bold">
                    Multiple Authentication Methods, Zero Hassle
                </h1>
                <p class="dark:text-gray-500 text-slate-900 lg:text-xl">
                    Forget about implementing separate verification systems. Our platform offers email, phone, and passwordless authentication right out of the box. No need to purchase separate SMS services for OTP delivery—we provide it for free. Our system handles verification codes, secure token management, and device authentication, allowing you to focus on building your core features rather than security infrastructure.
                </p>
                <div>
                    <a href="{{ route('contact') }}" class="dark:text-white/70 text-slate-900 px-4 py-2 rounded-full border border-slate-900 dark:border-white/40 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Developer Integration Section -->
            <div class="flex flex-col gap-8 max-w-4xl">
                <div>
                    <div class="w-16 md:w-20 lg:w-24 xl:w-28 aspect-square rounded-full border dark:border-indigo-400 border-secondary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-1/2 h-1/2 text-secondary dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <p class="font-semibold my-2 text-secondary dark:text-indigo-400">
                        Developer Friendly
                    </p>
                </div>
                <h1 class="dark:text-white/70 text-slate-900 text-3xl font-bold">
                    Effortless Integration, Powerful APIs
                </h1>
                <p class="dark:text-gray-500 text-slate-900 lg:text-xl">
                    Our API-first approach means developers can quickly implement secure authentication into any application. We provide comprehensive SDKs for all major platforms, detailed documentation, and code examples to get you started in minutes, not days. With OAuth 2.0 and OpenID Connect support, integrating with our service is straightforward and follows industry standards for maximum compatibility and security.
                </p>
                <div>
                    <a href="{{ route('clients.index') }}" class="dark:text-white/70 text-slate-900 px-4 py-2 rounded-full border border-slate-900 dark:border-white/40 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Try Our API
                    </a>
                </div>
            </div>

            <!-- Additional Feature Section -->
            <div class="flex flex-col gap-8 self-end max-w-4xl">
                <div>
                    <div class="w-16 md:w-20 lg:w-24 xl:w-28 aspect-square rounded-full border border-indigo-400 dark:border-secondary-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-1/2 h-1/2 text-indigo-400 dark:text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="font-semibold my-2 dark:text-secondary-600 text-indigo-400">
                        Enterprise Ready
                    </p>
                </div>
                <h1 class="dark:text-white/70 text-slate-900 text-3xl font-bold">
                    Scalable Security for Growing Businesses
                </h1>
                <p class="dark:text-gray-500 text-slate-900 lg:text-xl">
                    Our authentication platform grows with your business. From startups to enterprise-scale operations, we offer flexible plans with advanced features like multi-factor authentication, session management, and detailed analytics. With 99.9% uptime guarantee and enterprise-grade security practices, your authentication infrastructure is one less thing to worry about as your business scales.
                </p>
                <div>
                    <a href="{{ route('contact') }}" class="dark:text-white/70 text-slate-900 px-4 py-2 rounded-full border border-slate-900 dark:border-white/40 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Contact Sales
                    </a>
                </div>
            </div>

        </section>
    </main>

</x-home-layout>