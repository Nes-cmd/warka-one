<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Get Verification Code</h1>
        <p class="text-gray-600 dark:text-gray-400">Enter your details to receive a verification code</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <livewire-get-code-component :otpIsFor="$otpIsFor" />
</x-guest-layout>