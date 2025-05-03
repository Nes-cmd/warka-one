<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold dark:text-white">Verify Your Phone Number</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            We've sent a verification code to <span class="font-medium">{{ $phone }}</span>.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('verify-phone.verify') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="code" :value="__('Verification Code')" />
            <x-text-input id="code" 
                class="block mt-1 w-full text-center text-2xl tracking-wider" 
                type="text" 
                name="code" 
                :value="old('code')" 
                required 
                autofocus
                maxlength="6"
                autocomplete="one-time-code"
                inputmode="numeric"
                pattern="[0-9]*" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <form action="{{ route('verify-phone.resend') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                    {{ __('Resend Code') }}
                </button>
            </form>

            <x-primary-button class="bg-primary-600 hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 px-6 py-2.5 text-white font-medium transition-all duration-200">
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('profile.update-profile') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
            {{ __('Back to Profile') }}
        </a>
    </div>

    <script>
        // Auto format and validate code input
        document.getElementById('code').addEventListener('input', function(e) {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 6 digits
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
    </script>
</x-guest-layout> 