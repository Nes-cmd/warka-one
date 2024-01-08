<div>
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <div x-data="{
        authwith : $persist('{{ old('authwith')?old('authwith'):$authwith }}'),
        resendin : 90,
        }" 
        x-init="setInterval(() => {
                this.resendin = this.resendin - 1;
              }, 1000);
        ">


        <div class="relative mb-4" x-show="authwith == 'email'">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-200" type="email" name="email" disabled
                wire:model="email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div class="absolute right-3 top-[43%] ">
                <button wire:click="resendEmail" class="border rounded px-3 py-1 text-white bg-gray-700">Resend</button>
            </div>
        </div>


        <div class="h-[90px]" x-show="authwith == 'phone'">
            <x-input-label for="phone" :value="__('Phone')" />
            <div class="relative">
                <x-text-input id="phone" disabled
                    class="bg-gray-200 block mt-1 w-[71%] absolute right-0 rounded-l-none py-2" type="tel" name="phone"
                    wire:model="phone" required />

                <div class="absolute left-0 py-1 w-[30%]">
                    <div class="relative">
                        <!-- Button -->
                        <button x-ref="button" type="button"
                            class="flex items-center gap-2 bg-gray-200 px-5 py-[9px] rounded-md shadow">
                            <img class="w-[20px]" src="{{ asset($country->flag_url) }}" alt="">
                            <span>({{ $country->dial_code}})</span>

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>
                    </div>
                </div>

                <div class="absolute right-3 z-10 top-2">
                    <button wire:click="resendSMS" class="border rounded px-3 py-1 text-white bg-gray-700 text-sm"
                        x-text="'Resend in('+resendin+'s)'"></button>
                </div>

            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="verificationCode" :value="__('Verification code')" />
            <x-text-input id="" class="block mt-1 w-full" type="text" wire:model.lazy="verificationCode" required />
            <x-input-error :messages="$errors->get('verificationCode')" class="mt-2" />
        </div>



        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('get-otp') }}" class="border rounded-lg px-3 py-1 text-white bg-gray-500">&lt; Change</a>
            <x-primary-button wire:click="verify" class="ms-3 py-2">
                <span wire:loading class="loader mx-1"></span>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </div>
</div>