<div>
    <x-input-label for="phone" :value="__('Phone Number')" />
    <div class="flex mt-1">
        <select 
            wire:model="country_id" 
            class="rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 shadow-sm"
            {{ $isVerifying ? 'disabled' : '' }}
        >
            @foreach($countries as $country)
                <option value="{{ $country->id }}">
                    {{ $country->dial_code }}
                </option>
            @endforeach
        </select>
        <x-text-input 
            id="phone" 
            wire:model.defer="phone" 
            class="block w-full rounded-l-none {{ !$user->phone_verified_at && $user->phone ? 'rounded-r-none border-r-0' : '' }}" 
            type="text" 
            {{ $isVerifying ? 'disabled' : '' }}
        />
        
        @if(!$user->phone_verified_at && $user->phone)
            <div class="flex items-center">
                <span class="px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-r-md text-xs whitespace-nowrap">
                    Unverified
                </span>
            </div>
        @elseif($user->phone_verified_at && $user->phone)
            <div class="flex items-center">
                <span class="px-3 py-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-800 rounded-r-md text-xs whitespace-nowrap flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Verified
                </span>
            </div>
        @endif
    </div>
    
    @if($user->phone && $user->phone != $phone && !$isVerifying)
        <div class="mt-2 flex">
            <button 
                type="button"
                wire:click="savePhone" 
                wire:loading.attr="disabled" 
                class="text-sm text-primary-600 dark:text-primary-400 hover:underline flex items-center mr-3"
            >
                <span wire:loading.remove wire:target="savePhone">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Save New Phone
                </span>
                <span wire:loading wire:target="savePhone">
                    <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    @endif
    
    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    
    @if($verificationMessage && isset($messageType))
        <div class="mt-2 {{ $messageType === 'success' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} text-sm">
            {{ $verificationMessage }}
        </div>
    @endif
    
    @if(!$user->phone_verified_at && $user->phone && !$isVerifying)
        <div class="mt-2">
            <button 
                type="button"
                wire:click="initiateVerification" 
                wire:loading.attr="disabled" 
                class="text-sm text-primary-600 dark:text-primary-400 hover:underline flex items-center"
            >
                <span wire:loading.remove wire:target="initiateVerification">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Verify Phone Number
                </span>
                <span wire:loading wire:target="initiateVerification">
                    <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </div>
    @endif
    
    @if($isVerifying && $verificationSent)
        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
            <h4 class="text-md font-medium mb-2">Enter Verification Code</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                We've sent a 6-digit code to your phone. Enter it below to verify your number.
            </p>
            
            <div>
                <x-text-input 
                    wire:model.defer="verificationCode" 
                    class="block w-full text-center text-xl tracking-wider dark:bg-gray-800 dark:border-gray-700 dark:text-white" 
                    type="text" 
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    placeholder="000000"
                />
                <x-input-error :messages="$errors->get('verificationCode')" class="mt-2" />
            </div>
            
            <div class="flex justify-between mt-4">
                <div>
                    <button 
                        type="button"
                        wire:click="resendCode" 
                        wire:loading.attr="disabled" 
                        wire:loading.class="opacity-50"
                        class="text-sm text-primary-600 dark:text-primary-400 hover:underline inline-flex items-center"
                    >
                        <span wire:loading.remove wire:target="resendCode">Resend Code</span>
                        <span wire:loading wire:target="resendCode">Sending...</span>
                    </button>
                    <span class="mx-2 text-gray-400">|</span>
                    <button 
                        type="button"
                        wire:click="cancelVerification" 
                        class="text-sm text-gray-600 dark:text-gray-400 hover:underline"
                    >
                        Cancel
                    </button>
                </div>
                
                <button 
                    type="button"
                    wire:click="verifyCode" 
                    wire:loading.attr="disabled" 
                    wire:loading.class="opacity-50"
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md font-medium text-sm transition-colors inline-flex items-center"
                >
                    <span wire:loading.remove wire:target="verifyCode">Verify</span>
                    <span wire:loading wire:target="verifyCode">
                        <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verifying...
                    </span>
                </button>
            </div>
        </div>
    @endif
</div> 