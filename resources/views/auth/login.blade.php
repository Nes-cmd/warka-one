<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <div class="flex-col flex gap-6">
        <p class="text-2xl dark:text-white font-semibold">Welcome back!</p>
        <p class="text-2xl dark:text-white font-semibold">Log in</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-8">

        <div x-data="{
                authwith: $persist('{{ old('authwith')?old('authwith'):$authwith }}')
            }">
            @csrf
            <input type="hidden" name="authwith" x-model="authwith">
            <div class="border border-radius-2 rounded flex justify-around py-2 mb-4 dark:border-gray-600">
                <button type="button" :class="authwith == 'email'?'bg-secondary-50 text-primary border-b-2 border-secondary':'bg-gray-100 text-gray-500'" class="w-[40%] py-2 rounded" x-on:click="() => {authwith = 'email'}">Email</button>
                <button type="button" :class="authwith == 'phone'?'bg-secondary-50 text-primary border-b-2 border-secondary':'bg-gray-100 text-gray-500'" class=" w-[40%] py-2 rounded" x-on:click="() => {authwith = 'phone'}">Phone</button>
            </div>

            <!-- Email Address -->
            <div class="relative mb-4" x-show="authwith == 'email'">
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="block mt-1 w-full dark:bg-gray-700 dark:text-white" type="email" name="email" :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                            <button type="button" x-ref="button" :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button" class="flex items-center bg-white dark:bg-gray-700 dark:text-white py-2.5 pl-2 rounded-l-none rounded-r-md shadow">
                                <img class="w-[20px]" src="{{ asset($selectedCountry->flag_url) }}" alt="">
                                <span>{{ $selectedCountry->dial_code }}</span>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" style="display: none;" class="absolute left-0  rounded-md bg-white shadow-md">
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


            <!-- Password -->
            <div class="mt-4 relative">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="passwordInput" class="dark:bg-gray-700 dark:text-white block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

                <button type="button" class="absolute inset-y-0 right-0 px-4 top-1/3 flex items-center dark:text-gray-400  hover:text-primary-500 focus:outline-none" onclick="togglePasswordVisibility()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="togglePasswordIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                        <path id="showIcon" style="display:none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <p class="text-xs mt-10 dark:text-gray-300">By continuing, you agree to our <a href="https://pms.kertech.co/legal/policies" class="text-primary-500">Terms of Service</a> and <a href="https://pms.kertech.co/legal/policies#" class="text-primary-500">Privacy Policy</a>.</p>
            <div class="w-full flex justify-center mt-2">
                <x-primary-button class="my-3 xl:w-2/3 w-full py-2 flex justify-center items-center text-xl rounded-full">
                    {{ __('Log in') }}
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
                    @if (Route::has('register'))
                    <a href="{{ route('get-otp', ['for' => 'register']) }}" class="dark:text-gray-300 text-sm text-gray-600 hover:text-primary-300 rounded-md focus:outline-none ">
                        {{ __('Dont have account? ') }} <span class=" underline">Sign up</span>

                    </a>
                    @endif

                </div>

            </div>
        </div>
    </form>
</x-guest-layout>