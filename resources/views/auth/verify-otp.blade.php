<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Verify Your Code</h1>
        <p class="text-gray-600 dark:text-gray-400">Enter the verification code sent to your device</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <livewire-verify-otp-component />
</x-guest-layout>