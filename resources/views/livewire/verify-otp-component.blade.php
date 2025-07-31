<div>
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ "We have sent a 6-digit verification code to your $authwith. Please enter the code below to continue." }}
    </div>

    <div x-data="{
        authwith : $persist('{{ old('authwith')?old('authwith'):$authwith }}'),
        resendin : $persist(59),
        code: ['', '', '', '', '', ''],
        currentIndex: 0,
        
        resetResend(id, shouldSend = true) {
            if(id == 'resendsms' && shouldSend && $wire){
                $wire.resendSMS();
            }
            if(id == 'resendemail' && shouldSend && $wire){
                $wire.resendEmail();
            }

            var resendButton = document.getElementById(id)
           
            resendButton.disabled = true
            resendButton.classList.add('bg-gray-400', 'cursor-not-allowed')
            resendButton.classList.remove('bg-primary-50', 'hover:bg-primary-100')

            const countdown = setInterval(() => {
                if (this.resendin === 0) {
                    clearInterval(countdown);
                    resendButton.innerText = `Resend Code` 
                    resendButton.disabled = false
                    resendButton.classList.add('bg-primary-50', 'hover:bg-primary-100')
                    resendButton.classList.remove('bg-gray-400', 'cursor-not-allowed')
                    this.resendin = 60
                } 
                else {
                    this.resendin--;
                    resendButton.innerText = `Resend in ${this.resendin}s`
                }
            }, 1000);
        },
        
        handleCodeInput(index, event) {
            const value = event.target.value;
            if (value.length > 0) {
                this.code[index] = value.slice(-1);
                if (index < 5) {
                    this.currentIndex = index + 1;
                    this.$nextTick(() => {
                        document.getElementById(`code-${index + 1}`).focus();
                    });
                }
            } else {
                this.code[index] = '';
                if (index > 0) {
                    this.currentIndex = index - 1;
                    this.$nextTick(() => {
                        document.getElementById(`code-${index - 1}`).focus();
                    });
                }
            }
            
            // Update Livewire model
            if ($wire) {
            $wire.verificationCode = this.code.join('');
            }
        },
        
        handleKeydown(index, event) {
            if (event.key === 'Backspace' && this.code[index] === '') {
                if (index > 0) {
                    this.currentIndex = index - 1;
                    this.$nextTick(() => {
                        document.getElementById(`code-${index - 1}`).focus();
                    });
                }
            }
        },
        
        init(){
            if(this.resendin < 60){
                this.resetResend(this.authwith == 'phone'?'resendsms':'resendemail', false)
            }
        }
    }" class="space-y-6">

        <!-- Contact Information Display -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-{{ $authwith === 'email' ? 'envelope' : 'phone' }} text-primary-600 dark:text-primary-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Code sent to:</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            @if($authwith == 'email')
                                {{ $email }}
                            @else
                                {{ $country->dial_code }} {{ $phone }}
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Resend Button -->
                <div wire:ignore>
                    <button id="{{ $authwith == 'phone' ? 'resendsms' : 'resendemail' }}" 
                            x-on:click="resetResend('{{ $authwith == 'phone' ? 'resendsms' : 'resendemail' }}')"  
                            class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 px-3 py-2 rounded-lg transition-colors duration-200">
                        Resend Code
                    </button>
                </div>
            </div>
        </div>

        <!-- Verification Code Input -->
        <div class="space-y-4">
            <x-input-label for="verificationCode" :value="__('Enter 6-digit code')" />
            
            <div class="flex justify-center space-x-2">
                @for($i = 0; $i < 6; $i++)
                <input type="text" 
                       id="code-{{ $i }}"
                       x-model="code[{{ $i }}]"
                       x-on:input="handleCodeInput({{ $i }}, $event)"
                       x-on:keydown="handleKeydown({{ $i }}, $event)"
                       class="w-12 h-12 text-center text-xl font-semibold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 bg-white dark:bg-gray-700 dark:text-white transition-all duration-200"
                       maxlength="1"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       autocomplete="one-time-code"
                       :class="{ 'border-primary-500 dark:border-primary-400': code[{{ $i }}] !== '' }"
                       x-ref="codeInput{{ $i }}">
                @endfor
            </div>
            
            <x-input-error :messages="$errors->get('verificationCode')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <x-primary-button wire:click="verify" 
                             class="w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
               
                <span wire:loading class="">
                    <svg class="animate-spin h-5 w-5 text-white mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                
                </span>

                <span class="">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ __('Verify Code') }}
                </span>
                
            </x-primary-button>
        </div>

        <!-- Back Link -->
        <div class="text-center pt-6">
            <a href="/authflow/get-otp?for={{$verificationFor}}" 
               class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                <i class="fas fa-arrow-left mr-1"></i>
                {{ __('Back to get code') }}
            </a>
        </div>
    </div>
</div>