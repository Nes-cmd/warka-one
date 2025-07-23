<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Reset Password</h1>
        <p class="text-gray-600 dark:text-gray-400">Enter your email or phone to receive a reset code</p>
    </div>

    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ __('Forgot your password? No problem. Just let us know your phone/email address and we will phone or email you a password reset code that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        
        <div x-data="{
            authwith : '{{ old('authwith')?old('authwith'): $authwith }}'
            }">

            <input type="hidden" name="authwith" :value="authwith" id="">
            <input type="hidden" name="country_id" value="{{ $selectedCountry->id }}" id="">

            <!-- Email Address -->
            <div x-show="authwith === 'email'" class="space-y-2">
                <div class="flex items-center justify-between">
                    <x-input-label for="email" :value="__('Email Address')" />
                    @if(count($options) > 1 && in_array('phone', $options))
                    <button type="button" 
                            class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                            x-on:click="authwith = 'phone'">
                        Use phone instead
                    </button>
                    @endif
                </div>
                <x-text-input id="email" class="block w-full dark:text-white" type="email" name="email" :value="old('email')" placeholder="Enter your email address" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div x-show="authwith === 'phone'" class="space-y-2">
                <div class="flex items-center justify-between">
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    @if(count($options) > 1 && in_array('email', $options))
                    <button type="button" 
                            class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                            x-on:click="authwith = 'email'">
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
                                  :value="old('phone')" placeholder="Enter your phone number" />
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <x-primary-button type="submit" 
                                 class="w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <span class="flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        {{ __('Get Reset Code') }}
                    </span>
                </x-primary-button>
            </div>

            <!-- Back to Login -->
            <div class="text-center pt-6">
                <a href="{{ route('login') }}" 
                   class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                    {{ __('Back to login') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>