<x-home-layout>

    <section class="max-w-7xl mx-auto  my-32 lg:px-0 px-4 relative">
        <h1 class="text-5xl font-semibold sm:text-start text-center  dark:text-white text-slate-900">
            Let's talk
        </h1>
        <div class="w-full flex sm:flex-row flex-col items-center my-10 gap-5">
            <p class="dark:text-white text-slate-900 sm:w-1/3 sm:px-0 px-4 self-start">
                Whether you have questions about our products and services, need technical support, or want to explore potential partnerships, our Contact Us page is your gateway to reaching out. Simply fill out the provided form, and we'll get back to you promptly. We appreciate your time and look forward to hearing from you soon!
            </p>



            <div class="sm:w-2/3 w-[90%] ">

                @if(session('success'))
                <div class="sm:w-2/3 w-[90%] p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-800/20 dark:text-green-400" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="sm:w-2/3 w-[90%] p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-800/20 dark:text-red-400" role="alert">
                    {{ session('error') }}
                </div>
                @endif


                <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" class="border dark:border-white/40 border-gray-400 flex flex-col p-5 justify-center gap-5 rounded-lg">

                    @csrf
                    <h1 class="dark:text-white text-slate-900 text-center">
                        Get in touch
                    </h1>
                    <div class="flex flex-col gap-4">
                        <label class="dark:text-white text-slate-900" for="contact">Phone or Email</label>
                        <input id="contact" name="contact" value="{{ old('contact') }}" class="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                        @error('contact')
                        <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-4">
                        <label class="dark:text-white text-slate-900" for="title">Title your request</label>
                        <input id="title" name="title" value="{{ old('title') }}" class="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                        @error('title')
                        <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-4">
                        <label class="dark:text-white text-slate-900" for="message">Your request</label>
                        <textarea id="message" name="message" rows="4" class="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('message') }}</textarea>
                        @error('message')
                        <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Simple CAPTCHA -->
                    <div class="flex flex-col gap-4">
                        <label class="dark:text-white text-slate-900" for="captcha">
                            Verify you're human: What is {{ session('captcha_num1', $captcha_num1) }} + {{ session('captcha_num2', $captcha_num2) }}?
                        </label>
                        <input id="captcha" name="captcha" type="number" class="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        @error('captcha')
                        <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-center">
                        <button id="submitBtn" type="submit" class="dark:text-white text-slate-900 px-4 py-2 my-7 rounded-full border border-slate-900 dark:border-white/40 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer inline-flex items-center">
                            <span>Submit</span>
                            <svg id="loadingSpinner" class="hidden w-5 h-5 ml-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </section>

    <script>
        // Form submission handling with loading state
        document.getElementById('contactForm').addEventListener('submit', function() {
            const button = document.getElementById('submitBtn');
            const spinner = document.getElementById('loadingSpinner');
            
            // Disable button and show spinner
            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            spinner.classList.remove('hidden');
            
            // Allow the form to submit
            return true;
        });
    </script>
</x-home-layout>