<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <div class="flex-col flex gap-6">
        <p class="text-2xl dark:text-white font-semibold">Welcome back!</p>
        <p class="text-2xl dark:text-white font-semibold">Log in</p>
    </div>

    <form @submit="isLoading = true" method="POST" action="{{ route('login') }}" class="mt-8"
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

            @if(count($options) > 1)
            <div class="border border-radius-2 rounded flex justify-around py-2 mb-4 dark:border-gray-600">
                <button type="button" :class="authwith == 'email'?'bg-secondary-50 text-primary border-b-2 border-secondary':'bg-gray-100 text-gray-800'" class="w-[40%] py-2 rounded" x-on:click="() => {authwith = 'email'; otpRequested = false;}">Email</button>
                <button type="button" :class="authwith == 'phone'?'bg-secondary-50 text-primary border-b-2 border-secondary':'bg-gray-100 text-gray-800'" class=" w-[40%] py-2 rounded" x-on:click="() => {authwith = 'phone'; otpRequested = false;}">Phone</button>
            </div>
            @endif



            <!-- Email Address -->
            <div class="relative mb-4" x-show="authwith == 'email'">
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="block mt-1 w-full dark:text-white" type="email" name="email" :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

                <!-- Authentication Type Status Messages (Email/Phone) -->
                <div x-show="authTypeMessage" x-transition class="mb-3 text-sm" :class="authTypeMessageType === 'success' ? ' text-green-700' : ' text-red-700'">
                    <span x-text="authTypeMessage"></span>
                </div>
            </div>



            <div class="h-16" x-show="authwith == 'phone'">
                <x-input-label for="phone" :value="__('Phone')" />

                <div class="mb-8 flex">

                    <div class="rounded">
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
                            <button type="button" x-ref="button" :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button" class="flex items-center bg-white dark:bg-gray-700 dark:text-white py-2.5 pl-2 rounded-l-md rounded-r-none shadow">
                                <img class="w-[20px]" src="{{ asset($selectedCountry->flag_url) }}" alt="">
                                <span>{{ $selectedCountry->dial_code }}</span>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" style="display: none;" class="absolute left-0   bg-white shadow-md">
                                @foreach($countries as $country)
                                <a href="#" x-on:click="open = false" class="flex items-center w-full first-of-type:rounded-t-md last-of-type:rounded-b-md py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500">
                                    <img class="w-[20px]" src="{{ asset($country->flag_url) }}" alt="">
                                    <span>({{ $country->dial_code }}) {{ $country->name }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <x-text-input id="phone" class="block w-[100%] rounded-l-none dark:text-white" type="tel" name="phone" value="{{ old('phone')}}" />
                </div>
            </div>
            <x-input-error x-show="authwith == 'phone'" :messages="$errors->get('phone')" class="mt-2" />

            <!-- Password Authentication -->
            <div class="mt-4 relative" x-show="authMethod == 'password'">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="passwordInput" class="dark:text-white block mt-1 w-full" type="password" name="password" autocomplete="current-password" />

                <button type="button" class="absolute inset-y-0 right-0 px-4 top-1/3 flex items-center dark:text-gray-400 hover:text-primary-500 focus:outline-none" onclick="togglePasswordVisibility()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="togglePasswordIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                        <path id="showIcon" style="display:none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- OTP Authentication -->
            <div class="mt-4 relative" x-show="authMethod == 'otp'">
                <div class="mb-4">
                    <x-input-label for="otp" :value="__('One-Time Password')" />
                    <div class="flex">
                        <x-text-input id="otp" class="dark:text-white block mt-1 w-full" type="text" name="otp" autocomplete="one-time-code" placeholder="Enter verification code" />

                        <button
                            type="button"
                            class="ml-2 mt-1 px-4 py-2 whitespace-nowrap bg-secondary-50 border border-secondary text-secondary-600 rounded hover:bg-secondary-100 disabled:bg-gray-300 disabled:text-gray-500"
                            x-on:click="requestLoginOTP()"
                            x-html="otpRequested && countdown > 0 ? `Resend in <span class='font-bold'>${countdown}s</span>` : 'Get Code'"
                            x-bind:disabled="otpRequested && countdown > 0"
                            id="getCodeBtn">
                            Get Code
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />

                    <!-- OTP Status Messages -->
                    <div x-show="otpMessage" x-transition class="text-sm" :class="otpMessageType === 'success' ? 'text-green-700' : 'text-red-700'">
                        <span x-text="otpMessage"></span>
                    </div>
                </div>

            </div>

            <p class="text-xs mt-10 dark:text-gray-300 text-center">By continuing, you agree to our <a href="{{ route('privacy-policy') }}" class="text-primary-500">Terms of Service</a> and <a href="{{ route('privacy-policy') }}" class="text-primary-500">Privacy Policy</a>.</p>

            <div class="w-full flex justify-center mt-2">
                <x-primary-button
                    type="submit"
                    class="my-3 xl:w-2/3 w-full py-2 flex justify-center items-center text-xl rounded-full"
                    x-bind:class="isLoading?'bg-primary-500':'bg-primary'"
                    x-bind:disabled="isLoading">
                    <span x-show="!isLoading">{{ __('Log in') }}</span>
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Loading...') }}
                    </span>
                </x-primary-button>
            </div>

            <!-- Remember Me -->


            <div class="flex flex-col gap-4 items-center justify-center mt-4">
                <div class="block">
                    @if (Route::has('password.request'))
                    <a href="{{ route('get-otp', ['for' => 'reset-password']) }}" class="underline text-sm text-black dark:text-gray-300 hover:text-primary-300 rounded-md focus:outline-none ">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif
                </div>
                <div>
                    @if (Route::has('register') && $registrationEnabled)
                    <a href="{{ route('get-otp', ['for' => 'register']) }}" class="dark:text-gray-300 text-sm text-gray-600 hover:text-primary-300 rounded-md focus:outline-none ">
                        {{ __('Dont have account? ') }} <span class=" underline">Sign up</span>
                    </a>
                    @endif

                </div>

            </div>
        </div>
    </form>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('passwordInput');
            var toggleIcon = document.getElementById('togglePasswordIcon');
            var showIcon = document.getElementById('showIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showIcon.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                showIcon.style.display = 'none';
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