<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Create Account</h1>
        <p class="text-gray-600 dark:text-gray-400">Join us and start your journey</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" 
          x-data="{authwith : '{{ $authflowData['authwith'] }}'}"
          class="space-y-6">
        @csrf

        <input type="hidden" name="country_id" value="{{ $authflowData['country']->id }}" id="">

        <!-- Contact Information Display -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-{{ $authflowData['authwith'] === 'email' ? 'envelope' : 'phone' }} text-primary-600 dark:text-primary-400"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Registering with:</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        @if($authflowData['authwith'] == 'email')
                            {{ $authflowData['email'] }}
                        @else
                            {{ $authflowData['country']->dial_code }} {{ $authflowData['phone'] }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Full Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block w-full dark:text-white" type="text" name="name" 
                          :value="old('name')" required autofocus autocomplete="name" 
                          placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block w-full dark:text-white pr-12" type="password" 
                              name="password" required autocomplete="new-password" 
                              placeholder="Create a strong password" />
                
                <button type="button" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none" 
                        onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="togglePasswordIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block w-full dark:text-white pr-12" type="password" 
                              name="password_confirmation" required autocomplete="new-password" 
                              placeholder="Confirm your password" />
                
                <button type="button" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none" 
                        onclick="togglePasswordVisibility('password_confirmation', 'toggleConfirmPasswordIcon')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="toggleConfirmPasswordIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms Agreement -->
        <div class="pt-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 text-left">
                By creating an account, you agree to our 
                <a href="{{ route('privacy-policy') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400">Terms of Service</a> 
                and 
                <a href="{{ route('privacy-policy') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400">Privacy Policy</a>.
            </p>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button type="submit" 
                             class="w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <span class="flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('Create Account') }}
                </span>
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-6">
            <span class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Already have an account?') }}
            </span>
            <a href="{{ route('login') }}" 
               class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium ml-1">
                {{ __('Sign in') }}
            </a>
        </div>
    </form>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                `;
            }
        }
    </script>
</x-guest-layout>