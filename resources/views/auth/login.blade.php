<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome back!</h1>
        <p class="text-gray-600 dark:text-gray-400">Sign in to your account</p>
    </div>

    <form @submit="isLoading = true" method="POST" action="{{ route('login') }}" class="space-y-6"
        x-data="{ 
            isLoading: false,
            otpRequested: false,
            countdown: 60,
            authTypeMessage: '',
            authTypeMessageType: '',
            otpMessage: '',
            otpMessageType: '',
            updateAuthTypeStatus(message, type) {
                this.authTypeMessage = message;
                this.authTypeMessageType = type;
                this.otpMessage = '';
            },
            updateOtpStatus(message, type) {
                this.otpMessage = message;
                this.otpMessageType = type;
                this.authTypeMessage = '';
            },
            startOtpFlow() {
                this.otpRequested = true;
                this.countdown = 60;
                this.updateOtpStatus('Verification code sent successfully', 'success');
                this.startCountdown();
            },
            startCountdown() {
                const timer = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        clearInterval(timer);
                    }
                }, 1000);
            }
        }"
        @@otp-requested="startOtpFlow()"
        @@auth-type-error="updateAuthTypeStatus($event.detail.message, 'error')"
        @@otp-error="updateOtpStatus($event.detail.message, 'error')">

        <div x-data="{
                authwith: $persist('{{ $authwith }}'),
                authMethod: '{{ $authMethod ?? 'password' }}'
            }">
            @csrf
            <input type="hidden" name="authwith" x-model="authwith">
            <input type="hidden" name="auth_method" x-model="authMethod">

            <!-- Email Address -->
            <div x-show="authwith === 'email' || (count($options) === 1 && $options[0] === 'email')" class="space-y-2">
                <div class="flex items-center justify-between">
                    <x-input-label for="email" :value="__('Email Address')" />
                    @if(count($options) > 1)
                    <button type="button" 
                            class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                            x-on:click="authwith = 'phone'; otpRequested = false; authTypeMessage = ''; otpMessage = '';">
                        Use phone instead
                    </button>
                    @endif
                </div>
                <x-text-input id="email" class="block w-full dark:text-white" type="email" name="email" :value="old('email')" placeholder="Enter your email address" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

                <!-- Authentication Type Status Messages -->
                <div x-show="authTypeMessage" x-transition class="text-sm p-3 rounded-lg" 
                     :class="authTypeMessageType === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'">
                    <span x-text="authTypeMessage"></span>
                </div>
            </div>

            <!-- Phone Number -->
            <div x-show="authwith === 'phone' || (count($options) === 1 && $options[0] === 'phone')" class="space-y-2">
                <div class="flex items-center justify-between">
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    @if(count($options) > 1)
                    <button type="button" 
                            class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                            x-on:click="authwith = 'email'; otpRequested = false; authTypeMessage = ''; otpMessage = '';">
                        Use email instead
                    </button>
                    @endif
                </div>
                <div class="flex">
                    <div class="relative">
                        <div x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                                this.$refs.button.focus()
                                this.open = true
                            },
                            close(focusAfter) {
                                if (! this.open) return
                                this.open = false
                                focusAfter && focusAfter.focus()
                            }
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)" x-on:focusin.window="!$refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" class="relative">
                            <!-- Button -->
                            <button type="button" x-ref="button" :aria-expanded="open" :aria-controls="$id('dropdown-button')" 
                                    class="flex items-center bg-white dark:bg-gray-700 dark:text-white py-3 pl-3 pr-2 rounded-l-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <img class="w-5 h-5 mr-2" src="{{ asset($selectedCountry->flag_url) }}" alt="">
                                <span class="text-sm font-medium">{{ $selectedCountry->dial_code }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left x-on:click.outside="close($refs.button)" 
                                 :id="$id('dropdown-button')" style="display: none;" 
                                 class="absolute left-0 top-full z-10 mt-1 w-64 bg-white dark:bg-gray-700 shadow-lg rounded-lg border border-gray-200 dark:border-gray-600 max-h-60 overflow-y-auto">
                                @foreach($countries as $country)
                                <button type="button" x-on:click="open = false" 
                                        class="flex items-center w-full px-3 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-600 first:rounded-t-lg last:rounded-b-lg">
                                    <img class="w-5 h-5 mr-3" src="{{ asset($country->flag_url) }}" alt="">
                                    <span class="font-medium">{{ $country->dial_code }}</span>
                                    <span class="ml-2 text-gray-500 dark:text-gray-400">{{ $country->name }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <x-text-input id="phone" class="block w-full rounded-l-none dark:text-white" type="tel" name="phone" 
                                  value="{{ old('phone')}}" placeholder="Enter your phone number" />
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Password Authentication -->
            <div x-show="authMethod === 'password'" class="py-3 mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative">
                    <x-text-input id="passwordInput" class="block w-full dark:text-white pr-12" type="password" 
                                  name="password" autocomplete="current-password" placeholder="Enter your password" />
                    
                    <button type="button" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none" 
                            onclick="togglePasswordVisibility()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="togglePasswordIcon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- OTP Authentication -->
            <div x-show="authMethod === 'otp'" class="space-y-4">
                <div class="space-y-2">
                    <x-input-label for="otp" :value="__('Verification Code')" />
                    <div class="flex space-x-3">
                        <x-text-input id="otp" class="block w-full dark:text-white" type="text" name="otp" 
                                      autocomplete="one-time-code" placeholder="Enter verification code" />
                        
                        <button type="button" 
                                class="px-4 py-2 whitespace-nowrap bg-primary-50 border border-primary-200 text-primary-700 rounded-lg hover:bg-primary-100 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors duration-200 text-sm font-medium"
                                x-on:click="requestLoginOTP()"
                                x-html="otpRequested && countdown > 0 ? `Resend in <span class='font-bold'>${countdown}s</span>` : 'Get Code'"
                                x-bind:disabled="otpRequested && countdown > 0"
                                id="getCodeBtn">
                            Get Code
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />

                    <!-- OTP Status Messages -->
                    <div x-show="otpMessage" x-transition class="text-sm p-3 rounded-lg" 
                         :class="otpMessageType === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'">
                        <span x-text="otpMessage"></span>
                    </div>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="pt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 text-left">
                    By continuing, you agree to our 
                    <a href="{{ route('privacy-policy') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400">Terms of Service</a> 
                    and 
                    <a href="{{ route('privacy-policy') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400">Privacy Policy</a>.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <x-primary-button type="submit" 
                                 class="w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                 x-bind:class="isLoading ? 'bg-primary-400 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700'"
                                 x-bind:disabled="isLoading">
                    <span x-show="!isLoading" class="flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        {{ __('Sign In') }}
                    </span>
                    <span x-show="isLoading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Signing In...') }}
                    </span>
                </x-primary-button>
            </div>

            <!-- Links Section -->
            <div class="space-y-4 pt-6">
                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                <div class="text-center">
                    <a href="{{ route('get-otp', ['for' => 'reset-password']) }}" 
                       class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
                @endif

                <!-- Sign Up -->
                @if (Route::has('register') && $registrationEnabled)
                <div class="text-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Don\'t have an account?') }}
                    </span>
                    <a href="{{ route('get-otp', ['for' => 'register']) }}" 
                       class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium ml-1">
                        {{ __('Sign up') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </form>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('passwordInput');
            var toggleIcon = document.getElementById('togglePasswordIcon');

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

        function requestLoginOTP() {
            const form = document.querySelector('form');
            const authWith = form.querySelector('input[name="authwith"]').value;
            const email = authWith === 'email' ? form.querySelector('input[name="email"]').value : '';
            const phone = authWith === 'phone' ? form.querySelector('input[name="phone"]').value : '';

            if ((authWith === 'email' && !email) || (authWith === 'phone' && !phone)) {
                form.dispatchEvent(new CustomEvent('auth-type-error', {
                    detail: {
                        message: `Please enter your ${authWith} first`
                    }
                }));
                return;
            }

            const csrfToken = document.querySelector('input[name="_token"]').value;
            const getCodeBtn = document.getElementById('getCodeBtn');
            getCodeBtn.disabled = true;

            fetch('{{ route("request-login-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        auth_with: authWith,
                        email: email,
                        phone: phone
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        form.dispatchEvent(new CustomEvent('otp-requested'));
                    } else {
                        const isAuthTypeError = data.message && (
                            data.message.toLowerCase().includes('email') ||
                            data.message.toLowerCase().includes('phone')
                        );

                        if (isAuthTypeError) {
                            form.dispatchEvent(new CustomEvent('auth-type-error', {
                                detail: {
                                    message: data.message || 'Invalid email or phone number'
                                }
                            }));
                        } else {
                            form.dispatchEvent(new CustomEvent('otp-error', {
                                detail: {
                                    message: data.message || 'Failed to send verification code. Please try again.'
                                }
                            }));
                        }
                        getCodeBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    form.dispatchEvent(new CustomEvent('otp-error', {
                        detail: {
                            message: 'An error occurred. Please try again later.'
                        }
                    }));
                    getCodeBtn.disabled = false;
                });
        }
    </script>
</x-guest-layout>