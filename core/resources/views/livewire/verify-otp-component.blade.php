<div>
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />
    <div class="mb-4 text-sm text-gray-600">
        {{ "We have sent a 4-digit code to your $authwith , Use that code to verify "}}
    </div>
    <div x-data="{
        authwith : $persist('{{ old('authwith')?old('authwith'):$authwith }}'),
        resendin :  $persist(60),
        resetResend(id) {
            if(id == 'resendsms'){
                $wire.resendSMS();
            }
            if(id == 'resendemail'){
                $wire.resendEmail();
            }

            var resendButton = document.getElementById(id)
           
            resendButton.disabled = true

            resendButton.classList.add('bg-gray-400')
            resendButton.classList.remove('bg-gray-700')

            const countdown = setInterval(() => {
                if (this.resendin === 0) {
                    clearInterval(countdown);
                    resendButton.innerText = `Resend` 
                    resendButton.disabled = false

                    resendButton.classList.add('bg-gray-700')
                    resendButton.classList.remove('bg-gray-400')

                    this.resendin = 60
                } 
                else {
                    this.resendin--;
                    resendButton.innerText = `Resend in ${this.resendin} s`
                }

            }, 1000);
        }
        }"
        >


        <div class="relative mb-4" x-show="authwith == 'email'">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-200" type="email" name="email" disabled
                wire:model="email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div class="absolute right-3 top-[43%] " wire:ignore>
                <button id="resendemail" x-on:click="resetResend('resendemail')" class="border rounded px-3 py-1 text-white bg-gray-700"
                    >Resend</button>
            </div>
        </div>


        <div class="h-[90px]" x-show="authwith == 'phone'">
            <x-input-label for="phone" :value="__('Phone')" />
            <div class="relative">
                <x-text-input id="phone" disabled
                    class="bg-gray-200 block mt-1 w-[71%]  absolute right-0 rounded-l-none py-2 pl-5 md:pl-3" type="tel" name="phone"
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

                <div class="absolute right-3 z-10 top-2" wire:ignore>
                    <button id="resendsms"  x-on:click="resetResend('resendsms')" class="bg-gray-700 border rounded px-3 py-1 text-white text-sm"
                       >Resend</button>
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
            <a href="/authflow/get-otp?for={{$verificationFor}}" class="border rounded-lg px-3 py-1 text-white bg-gray-500">&lt; Change</a>
            <x-primary-button wire:click="verify" class="ms-3 py-2">
                <span wire:loading class="loader mx-1"></span>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </div>

    <script>

        // function resetResend(id) {
        //     var seconds = 10;
        //     var resendButton = document.getElementById(id)
        //     resendButton.disabled = true;
        //     resendButton.classList.remove('bg-gray-700')
        //     resendButton.classList.add('bg-gray-400')
        //     const countdown = setInterval(() => {
        //         if (seconds === 0) {
        //             clearInterval(countdown);
        //             resendButton.innerText = `Resend`
        //             resendButton.disabled = false
        //             resendButton.classList.remove('bg-gray-400')
        //             resendButton.classList.add('bg-gray-700')
        //             console.log("Countdown finished!");
        //         } 
        //         else {
        //             seconds--;
        //             resendButton.innerText = `Resend in ${seconds} s`
        //         }

        //     }, 1000);
        // }
    </script>

</div>