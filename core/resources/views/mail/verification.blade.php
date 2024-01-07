<x-mail::panel>
    <x-mail::message>
        # A verification code

        Your verification code is {{ $verificationCode }}

        Thanks,<br>

        {{ config('app.name') }}
        
    </x-mail::message>
</x-mail::panel>