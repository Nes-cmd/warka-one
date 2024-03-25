<div>
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />
    <div class="mb-4 text-sm text-gray-600">
        {{ "We have sent a 4-digit code to your $authwith , Use that code to verify "}}
    </div>
    <div x-data="{
        authwith : $persist('{{ old('authwith')?old('authwith'):$authwith }}'),
        resendin :  $persist(59),
        resetResend(id, shouldSend = true) {
            if(id == 'resendsms' && shouldSend){
                $wire.resendSMS();
            }
            if(id == 'resendemail' && shouldSend){
                $wire.resendEmail();
            }

            var resendButton = document.getElementById(id)
           
            resendButton.disabled = true

            resendButton.classList.add('bg-gray-400')
            resendButton.classList.remove('bg-secondary-50/100')

            const countdown = setInterval(() => {
                if (this.resendin === 0) {
                    clearInterval(countdown);
                    resendButton.innerText = `Resend` 
                    resendButton.disabled = false

                    resendButton.classList.add('bg-secondary-50/100')
                    resendButton.classList.remove('bg-gray-400')

                    this.resendin = 60
                } 
                else {
                    this.resendin--;
                    resendButton.innerText = `Resend in ${this.resendin} s`
                }

            }, 1000);
        },
        init(){
            if(this.resendin < 60){
                this.resetResend(this.authwith == 'phone'?'resendsms':'resendemail', false)
            }
        }
        }"
        
        >

        @if($authwith == 'email')
        <div class="relative mb-4" x-show="authwith == 'email'">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-200" type="email" name="email" disabled wire:model="email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div class="absolute right-3 top-[43%] " wire:ignore>
                <button id="resendemail" x-on:click="resetResend('resendemail')"  class="border border-secondary rounded px-3 py-1 text-secondary-600 bg-secondary-50/100">Resend</button>
            </div>
        </div>
        @endif

        @if($authwith == 'phone')
        <div class="h-[90px]" x-show="authwith == 'phone'">
            <x-input-label for="phone" :value="__('Phone')" />
            <div class="flex relative">
                
                <div class="">
                    <div class="">
                        <!-- Button -->
                        <button x-ref="button" type="button" class="flex items-center bg-gray-200 pl-2 py-2.5 rounded-md shadow">
                            <img class="w-[20px]" src="{{ asset($country->flag_url) }}" alt="">
                            <span>({{ $country->dial_code}})</span>

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>

                        </button>
                    </div>
                </div>

                <x-text-input id="phone" disabled class="bg-gray-200 block  w-[90%] py-2.5" type="tel" name="phone" wire:model="phone" required />


                <div class="absolute right-3 z-10 top-2" wire:ignore>
                    <button id="resendsms" x-on:click="resetResend('resendsms')" class="border border-secondary rounded px-3 py-1 text-secondary-600 bg-secondary-50/100">Resend</button>
                </div>
                

            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        @endif

        
        <div>
            <x-input-label for="verificationCode" :value="__('Verification code')" />
            <x-text-input id="" class="block mt-1 w-full" type="text" wire:model.lazy="verificationCode" required />
            <x-input-error :messages="$errors->get('verificationCode')" class="mt-2" />
        </div>
        

        <div class="flex items-center justify-between mt-4">
            <a href="/authflow/get-otp?for={{$verificationFor}}" class="border border-secondary rounded-lg px-3 py-1 text-secondary-600 bg-secondary-50/100">&lt; Go Back</a>

        </div>
        <div class="w-full flex justify-center mt-2">
            <x-primary-button wire:click="verify" class="my-3 xl:w-2/3 w-full py-2 flex justify-center items-center text-xl rounded-full">
                <span wire:loading class="loader mx-1"></span>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
        <!-- <x-primary-button wire:click="verify" class="ms-3 py-2">
            <span wire:loading class="loader mx-1"></span>
            {{ __('Verify') }}
        </x-primary-button> -->

    </div>

</div>