<div>
    @if($otpIsFor == 'reset-password')
    <div class="mb-4 text-sm text-gray-600 dark:text-white">
        {{ __('Don\'t worry if you forgot your password. Simply provide us with your phone/email, and we\'ll send a verification code. Once verified, you can easily reset your password.') }}
    </div>
    @else

    <div class="mb-4 text-sm text-gray-600 dark:text-white">
        <p class="mb-4 text-3xl font-semibold">
            Welcome !
        </p>
        {{ __('Please enter your phone/email. You will receive a text message to verify your account.') }}
    </div>
    @endif


    <div x-data="{
        authwith : $persist( '{{ $authwith }}' )
        }" x-init="
        $wire.authwith = authwith;
        console.log(authwith)
        ">

        @if(count($options) > 1 && in_array('email', $options) && in_array('phone', $options))
        <div class="border border-radius-2 rounded dark:border-gray-600 flex justify-around py-2 mb-4">
            <button :class="authwith == 'email'?'bg-secondary-50 text-primary border-b-2 border-secondary':'bg-gray-100 text-gray-500'" class="w-[40%] py-2 rounded" x-on:click="() => {authwith = 'email'; $wire.authwith='email'}">Email</button>
            <button :class="authwith == 'phone'?'bg-secondary-50 text-primary border-b-2 border-secondary':'bg-gray-100 text-gray-500'" class=" w-[40%] py-2 rounded" x-on:click="() => {authwith = 'phone'; $wire.authwith='phone'}">Phone</button>
        </div>
        @endif
        <!-- Email Address -->
        <div class="relative mb-4" x-show="authwith == 'email'">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model.lazy="email" id="email" class="block mt-1 w-full dark:text-white" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <!-- <div class="absolute right-3 top-[43%] ">
                <button class="border rounded px-3 py-1 text-white bg-blue-500">Get code</button>
            </div> --> 
        </div>

        <div x-show="authwith == 'phone'">
            <x-input-label for="phone" :value="__('Phone')" />
            <div class="flex mb-8">
                
                <div>
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
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)" x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" class="relative">
                        <!-- Button -->
                        <button type="button" x-ref="button" :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button" class="flex items-center bg-white  dark:bg-gray-700 dark:text-white py-2.5 pl-2 rounded-l-md shadow">
                                <img class="w-[20px]" src="{{ asset($selectedCountry->flag_url) }}" alt="">
                                <span>({{ $selectedCountry->dial_code }})</span>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                        <!-- Panel -->
                        <div x-ref="panel" x-show="open" x-transition.origin.top.left x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" style="display: none;" class="absolute left-0 rounded-md bg-white shadow-md">
                            @foreach($countries as $country)
                            <a href="#" x-on:click="open = false" class="flex items-center gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md px-4 py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500">
                                <img class="w-[20px]" src="{{ asset($country->flag_url) }}" alt="">
                                <span>({{ $country->dial_code }}) {{ $country->name }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <x-text-input wire:model.lazy="phone" id="phone" type="tel" class="block w-[100%]  dark:text-white rounded-l-none"  name="phone" :value="old('phone')" required />


            </div>
        </div>
        <x-input-error x-show="authwith == 'phone'" :messages="$errors->get('phone')" class="mt-2" />
        
        <div class="flex justify-center w-full">
            <x-primary-button wire:click="getCode" class="mt-8 py-2 xl:w-[80%] w-full rounded-full justify-center">
                <span wire:loading class="mx-2">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                </span>
                {{ __('Get code') }}
            </x-primary-button>
        </div>


        <div class="w-full flex items-center justify-center mt-4 text-secondary-400 hover:text-primary-300">
            @if (Route::has('login'))
            <a href="{{ route('login',) }}" class=" text-sm rounded-md focus:outline-none ">
                {{ __('Back to login?') }} <span class="underline dark:text-gray-200 hover:text-primary-300">Log in</span>
            </a>
            @endif

        </div>
    </div>

</div>