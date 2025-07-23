<div>
    @if($otpIsFor == 'reset-password')
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ __('Don\'t worry if you forgot your password. Simply provide us with your phone/email, and we\'ll send a verification code. Once verified, you can easily reset your password.') }}
    </div>
    @else
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ __('Please enter your phone/email. You will receive a text message to verify your account.') }}
    </div>
    @endif

    <div x-data="{
        authwith : $persist( '{{ $authwith }}' )
        }" x-init="
        $wire.authwith = authwith;
        console.log(authwith)
        " class="space-y-6">

        <!-- Email Address -->
        <div x-show="authwith === 'email'" class="space-y-2">
            <div class="flex items-center justify-between">
                <x-input-label for="email" :value="__('Email Address')" />
                @if(count($options) > 1 && in_array('phone', $options))
                <button type="button" 
                        class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                        x-on:click="() => {authwith = 'phone'; $wire.authwith='phone'}">
                    Use phone instead
                </button>
                @endif
            </div>
            <x-text-input wire:model.lazy="email" id="email" class="block w-full dark:text-white" type="email" name="email" placeholder="Enter your email address" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div x-show="authwith === 'phone'" class="space-y-2">
            <div class="flex items-center justify-between">
                <x-input-label for="phone" :value="__('Phone Number')" />
                @if(count($options) > 1 && in_array('email', $options))
                <button type="button" 
                        class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                        x-on:click="() => {authwith = 'email'; $wire.authwith='email'}">
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
                            <button type="button" x-on:click="open = false; $wire.changeCountry({{ $country->id }})" 
                                    class="flex items-center w-full px-3 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-600 first:rounded-t-lg last:rounded-b-lg">
                                <img class="w-5 h-5 mr-3" src="{{ asset($country->flag_url) }}" alt="">
                                <span class="font-medium">{{ $country->dial_code }}</span>
                                <span class="ml-2 text-gray-500 dark:text-gray-400">{{ $country->name }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <x-text-input wire:model.lazy="phone" id="phone" class="block w-full rounded-l-none dark:text-white" type="tel" name="phone" 
                              placeholder="Enter your phone number" required />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <x-primary-button wire:click="getCode" 
                             class="w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:ring-2 focus:ring-primary-500">
                
                    <span wire:loading class="">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  
                </span>
                <span class="">
                    <i class="fas fa-paper-plane mr-2"></i>
                    {{ __('Get Verification Code') }}
                </span>

                
            </x-primary-button>
        </div>

        <!-- Back to Login -->
        <div class="text-center pt-6">
            @if (Route::has('login'))
            <a href="{{ route('login') }}" 
               class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                {{ __('Back to login') }}
            </a>
            @endif
        </div>
    </div>
</div>