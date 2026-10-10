<?php

namespace Tests\Feature;

use App\Mail\AccountActivatedMail;
use App\Mail\AktivasiAkunMail;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AccountActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_activation_email_contains_an_absolute_url_with_a_relative_signature(): void
    {
        $user = $this->createInactiveUser();

        $mail = new AktivasiAkunMail($user);

        $this->assertStringStartsWith(rtrim((string) config('app.url'), '/').'/', $mail->activationUrl);

        Mail::fake();

        $this->get($mail->activationUrl)
            ->assertRedirect('/admin/login');

        $this->assertSame('aktif', $user->refresh()->status_keanggotaan);
        Mail::assertSent(AccountActivatedMail::class, 1);
    }

    public function test_legacy_absolute_signature_remains_valid(): void
    {
        $user = $this->createInactiveUser();
        $url = URL::temporarySignedRoute(
            'akun.aktivasi',
            now()->addHour(),
            ['user' => $user->getKey()],
        );

        Mail::fake();

        $this->get($url)
            ->assertRedirect('/admin/login');

        $this->assertSame('aktif', $user->refresh()->status_keanggotaan);
    }

    public function test_valid_link_for_a_missing_account_redirects_to_registration(): void
    {
        $url = URL::temporarySignedRoute(
            'akun.aktivasi',
            now()->addHour(),
            ['user' => 999999],
            absolute: false,
        );

        $this->get($url)
            ->assertRedirect('/admin/register');
    }

    private function createInactiveUser(): User
    {
        $division = Divisi::query()->create([
            'nama' => 'Pengembangan Web dan Teknologi Informasi',
            'singkatan' => 'PWTI',
        ]);

        return User::query()->create([
            'nama' => 'Pengguna Uji',
            'email' => 'activation@example.com',
            'nim' => '10101010',
            'password' => 'password',
            'id_divisi' => $division->getKey(),
            'status_keanggotaan' => 'nonaktif',
        ]);
    }
}
