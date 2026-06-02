<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpSessionBindingTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_otp_cannot_be_verified_in_a_different_session(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'email_verified_at' => now(),
        ]);

        $this->post(route('v2.password.email'), [
            'authwith' => 'email',
            'email' => $user->email,
        ])->assertRedirect(route('v2.authflow.verify'));

        $otp = VerificationCode::where('candidate', $user->email)->value('verification_code');
        $this->assertNotNull($otp);

        $this->flushSession();

        session()->put('authflow', [
            'authwith' => 'email',
            'email' => $user->email,
            'phone' => null,
            'country' => null,
            'country_id' => null,
            'otpIsFor' => 'reset-password',
        ]);

        $this->post(route('v2.authflow.verify.store'), [
            'verificationCode' => $otp,
        ])->assertSessionHasErrors('verificationCode');

        $this->assertDatabaseHas('verification_codes', [
            'candidate' => $user->email,
            'status' => 'sent',
        ]);
    }

    public function test_password_reset_otp_succeeds_in_the_same_session(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'email_verified_at' => now(),
        ]);

        $this->post(route('v2.password.email'), [
            'authwith' => 'email',
            'email' => $user->email,
        ])->assertRedirect(route('v2.authflow.verify'));

        $otp = VerificationCode::where('candidate', $user->email)->value('verification_code');

        $this->post(route('v2.authflow.verify.store'), [
            'verificationCode' => $otp,
        ])->assertRedirect(route('v2.password.reset'));

        $this->assertDatabaseHas('verification_codes', [
            'candidate' => $user->email,
            'status' => 'verified',
        ]);
    }
}
