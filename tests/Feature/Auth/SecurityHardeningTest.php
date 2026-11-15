<?php

namespace Tests\Feature\Auth;

use App\Mail\VerificationTokenMail;
use App\Models\User;
use Database\Seeders\RoleAndUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    private const LOGIN = '/kec-pangalengan-login-access';

    // ---------- Temuan 1: rate limit login ----------

    public function test_login_is_locked_after_five_failed_attempts(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post(self::LOGIN, ['email' => $user->email, 'password' => 'salah'])
                ->assertSessionHasErrors('email');
        }

        // Percobaan ke-6 diblokir, bahkan dengan password yang benar.
        $this->post(self::LOGIN, ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_successful_login_still_works(): void
    {
        $user = User::factory()->create();

        $this->post(self::LOGIN, ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    // ---------- Temuan 3: pesan seragam ----------

    public function test_login_error_is_identical_for_unknown_wrong_password_and_pending_accounts(): void
    {
        $verified = User::factory()->create();
        $pending  = User::factory()->unverified()->create();

        $messages = collect([
            ['email' => 'tidak-ada@example.com', 'password' => 'x'],
            ['email' => $verified->email, 'password' => 'salah'],
            ['email' => $pending->email, 'password' => 'salah'],
        ])->map(function ($cred) {
            $response = $this->post(self::LOGIN, $cred);
            $response->assertSessionHasErrors('email');

            return session('errors')->first('email');
        })->unique();

        $this->assertCount(1, $messages);
    }

    public function test_entry_and_token_pages_do_not_reveal_whether_account_exists(): void
    {
        $verified = User::factory()->create();
        $pending  = User::factory()->unverified()->create();

        foreach (['x@nope.test', $verified->email, $pending->email] as $email) {
            $this->get(route('register.complete.show', ['email' => $email]))
                ->assertOk()
                ->assertDontSee($pending->name);

            $this->get(route('verification.token.show', ['email' => $email]))->assertOk();
        }
    }

    // ---------- Temuan 2: token di-hash + throttle ----------

    public function test_verification_token_is_stored_hashed_and_can_verify(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create();

        $this->post(route('register.complete.store'), [
            'email' => $user->email,
            'name' => 'Nama Baru',
            'password' => 'Password-Kuat-123!',
            'password_confirmation' => 'Password-Kuat-123!',
        ])->assertRedirect();

        $plain = null;
        Mail::assertSent(VerificationTokenMail::class, function ($mail) use (&$plain) {
            $plain = $mail->token;

            return true;
        });

        $stored = $user->fresh()->verification_token;
        $this->assertNotSame($plain, $stored);
        $this->assertSame(User::hashVerificationToken($plain), $stored);

        $this->post(route('verification.token.verify'), ['email' => $user->email, 'token' => $plain])
            ->assertRedirect(route('dashboard'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_wrong_token_is_rejected_and_attempts_are_throttled(): void
    {
        $user = User::factory()->unverified()->create([
            'verification_token' => User::hashVerificationToken('123456'),
            'verification_token_expires_at' => now()->addMinutes(3),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('verification.token.verify'), ['email' => $user->email, 'token' => '000000'])
                ->assertSessionHasErrors('token');
        }

        // Percobaan ke-6 kena throttle, meskipun tokennya benar.
        $this->post(route('verification.token.verify'), ['email' => $user->email, 'token' => '123456'])
            ->assertStatus(429);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_resend_token_is_throttled(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();

        for ($i = 0; $i < 3; $i++) {
            $this->post(route('verification.token.resend'), ['email' => $user->email])->assertRedirect();
        }

        $this->post(route('verification.token.resend'), ['email' => $user->email])->assertStatus(429);
        Mail::assertSentCount(3);
    }

    // ---------- Temuan 4: seeder ----------

    public function test_role_and_user_seeder_runs_with_configured_accounts(): void
    {
        foreach (['gizi_pangalengan', 'gizi_warnasari', 'gizi_sukamanah', 'camat', 'admin'] as $key) {
            config([
                "seeder.accounts.{$key}.email" => "{$key}@example.test",
                "seeder.accounts.{$key}.password" => 'Seed-Password-123!',
            ]);
        }

        $this->seed(RoleAndUserSeeder::class);

        $admin = User::where('email', 'admin@example.test')->firstOrFail();
        $this->assertTrue($admin->hasRole('IT Admin'));
        $this->assertTrue(Hash::check('Seed-Password-123!', $admin->password));
        $this->assertTrue(User::where('email', 'camat@example.test')->firstOrFail()->hasRole('Camat'));
    }

    public function test_seeder_fails_loudly_when_env_values_are_missing(): void
    {
        config(['seeder.accounts.admin.email' => null, 'seeder.accounts.admin.password' => null]);

        $this->expectException(\RuntimeException::class);
        $this->seed(RoleAndUserSeeder::class);
    }

    // ---------- Temuan 5: route debug ----------

    public function test_debug_memory_route_is_gone(): void
    {
        $this->get('/mem-test')->assertNotFound();
    }
}
