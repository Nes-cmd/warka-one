<div>
    @if($otpIsFor == 'reset-password')
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your phone/email address and we will phone or email
        you a password reset code that will allow you to choose a new one.') }}
    </div>
    @endif

    <div x-data="{
        authwith : '{{ $authwith }}'
        }">

        <div class="border border-radius-2 rounded flex justify-around py-2 mb-4">
            <button :class="authwith == 'phone'?'bg-gray-600 text-white':'bg-gray-100 text-gray-900'" class=" w-[40%] py-2 rounded"
                x-on:click="() => {authwith = 'phone'; $wire.authwith = 'phone'}">Phone</button>
            <button :class="authwith == 'email'?'bg-gray-600 text-white':'bg-gray-100 text-gray-900'" class="w-[40%] py-2 rounded"
                x-on:click="() => {authwith = 'email'; $wire.authwith = 'email'}">Email</button>
        </div>

        <!-- Email Address -->
        <div class="relative mb-4" x-show="authwith == 'email'">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model.lazy="email" id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <!-- <div class="absolute right-3 top-[43%] ">
                <button class="border rounded px-3 py-1 text-white bg-blue-500">Get code</button>
            </div> -->
        </div>

        <div class="h-12" x-show="authwith == 'phone'">
            <div class="relative mb-8">
                <x-text-input wire:model.lazy="phone" id="phone"
                    class="block mt-1 w-[71%] absolute right-0 rounded-l-none py-2" type="tel" name="phone"
                    :value="old('phone')" required />

                <div class="absolute left-0 py-1 w-[30%]">
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
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                        x-id="['dropdown-button']" class="relative">
                        <!-- Button -->
                        <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                            :aria-controls="$id('dropdown-button')" type="button"
                            class="flex items-center gap-2 bg-white px-5 py-[9px] rounded-md shadow">
                            <img class="w-[20px]" src="{{ asset($selectedCountry->flag_url) }}" alt="">
                            <span>({{ $selectedCountry->dial_code }})</span>


                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Panel -->
                        <div x-ref="panel" x-show="open" x-transition.origin.top.left
                            x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" style="display: none;"
                            class="absolute left-0 mt-2 w-40 rounded-md bg-white shadow-md">
                            @foreach($countries as $country)
                            <a href="#" x-on:click="open = false"
                                class="flex items-center gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md px-4 py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500">
                                <img class="w-[20px]" src="{{ asset($country->flag_url) }}" alt="">
                                <span>({{ $country->dial_code }}) {{ $country->name }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-input-error x-show="authwith == 'phone'" :messages="$errors->get('phone')" class="mt-2" />


        <div class="flex items-center justify-end mt-4">
            <x-primary-button wire:click="getCode" class="ms-3 py-2">
                <span wire:loading class="loader mx-2"></span>
                {{ __('Get code') }}
            </x-primary-button>
        </div>
    </div>

</div>