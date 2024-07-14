<x-guest-layout class="w-[600px]">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('authstatus')" />

    <livewire-get-code-component :otpIsFor="$otpIsFor" />
</x-guest-layout>