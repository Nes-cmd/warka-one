<x-mail::panel>
    <x-mail::message>
    
        
            <div style="background-color: #ffffff; border-radius: 8px; padding: 15px 10px;">
                <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">Email Verification</h1>
                <p style="margin-bottom: 20px;">Hello,</p>
                <p style="margin-bottom: 30px;">Thank you for signing up. Please use the following verification code
                    to verify your email:</p>
                <div
                    style="background-color: #007bff; color: #ffffff; font-size: 26px; font-weight: bold; border-radius: 8px; padding: 12px; text-align: center; margin-bottom: 30px;">
                    <span style="display: block;">{{ $verificationCode }}</span>
                </div>
                <p style="margin-bottom: 30px;">Use this code to verify your email and complete the registration
                    process.</p>
                <p style="margin-bottom: 20px;">If you have any questions or need further assistance, please don't
                    hesitate to contact us.</p>
                <p style="margin-bottom: 20px;">Thank you,</p>
                <p style="color: #888888;">{{ env('APP_NAME') }}</p>
            </div>
        
        
    </x-mail::message>
</x-mail::panel>