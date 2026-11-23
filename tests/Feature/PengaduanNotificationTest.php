<?php

namespace Tests\Feature;

use App\Mail\NewComplaintMail;
use App\Models\Complaint;
use App\Models\Faskes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PengaduanNotificationTest extends TestCase
{
    use RefreshDatabase;

    private const PANGALENGAN = 'pangalengan@test.example';
    private const WARNASARI   = 'warnasari@test.example';
    private const SUKAMANAH   = 'sukamanah@test.example';
    private const CAMAT       = 'camat@test.example';
    private const ADMIN       = 'admin@test.example';

    protected function setUp(): void
    {
        parent::setUp();

        config(['pengaduan.notify' => [
            'puskesmas' => [
                'PANGALENGAN DTP' => self::PANGALENGAN,
                'WARNASARI'       => self::WARNASARI,
                'SUKAMANAH'       => self::SUKAMANAH,
            ],
            'camat' => self::CAMAT,
            'admin' => self::ADMIN,
        ]]);

        foreach ([
            ['PANGALENGAN DTP', 'PANGALENGAN', 'MELATI 1'],
            ['WARNASARI', 'WARNASARI', 'MAWAR 1'],
            ['SUKAMANAH', 'SUKAMANAH', 'MUTIARA 01'],
        ] as [$puskesmas, $desa, $nama]) {
            Faskes::create([
                'nama_faskes'    => $nama,
                'jenis'          => 'Posyandu',
                'desa_kelurahan' => $desa,
                'puskesmas'      => $puskesmas,
                'lat'            => -7.2,
                'lng'            => 107.5,
                'is_active'      => true,
            ]);
        }

        Mail::fake();
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'nama'           => 'Warga Rahasia',
            'kontak'         => '081299998888',
            'puskesmas'      => 'WARNASARI',
            'desa_kelurahan' => 'WARNASARI',
            'posyandu'       => 'MAWAR 1',
            'kategori'       => 'layanan_kesehatan',
            'judul'          => 'Posyandu jarang buka',
            'isi'            => 'Posyandu di RW 05 sudah dua bulan tidak buka sehingga balita tidak bisa ditimbang.',
        ], $override);
    }

    private function recipients(): array
    {
        return Mail::sent(NewComplaintMail::class)
            ->flatMap(fn ($m) => collect($m->to)->pluck('address'))
            ->sort()->values()->all();
    }

    public function test_complaint_emails_only_selected_puskesmas_plus_camat_and_admin(): void
    {
        $this->post(route('pengaduan.store'), $this->payload())->assertSessionHasNoErrors();

        $this->assertSame(
            collect([self::WARNASARI, self::CAMAT, self::ADMIN])->sort()->values()->all(),
            $this->recipients()
        );
        Mail::assertSent(NewComplaintMail::class, 3);
    }

    #[DataProvider('puskesmasProvider')]
    public function test_each_puskesmas_only_gets_its_own_complaints(string $puskesmas, string $desa, string $posyandu, string $expected, array $others): void
    {
        $this->post(route('pengaduan.store'), $this->payload([
            'puskesmas' => $puskesmas, 'desa_kelurahan' => $desa, 'posyandu' => $posyandu,
        ]))->assertSessionHasNoErrors();

        $got = $this->recipients();
        $this->assertContains($expected, $got);
        $this->assertContains(self::CAMAT, $got);
        $this->assertContains(self::ADMIN, $got);
        foreach ($others as $other) {
            $this->assertNotContains($other, $got);
        }
    }

    public static function puskesmasProvider(): array
    {
        return [
            'pangalengan' => ['PANGALENGAN DTP', 'PANGALENGAN', 'MELATI 1', self::PANGALENGAN, [self::WARNASARI, self::SUKAMANAH]],
            'warnasari'   => ['WARNASARI', 'WARNASARI', 'MAWAR 1', self::WARNASARI, [self::PANGALENGAN, self::SUKAMANAH]],
            'sukamanah'   => ['SUKAMANAH', 'SUKAMANAH', 'MUTIARA 01', self::SUKAMANAH, [self::PANGALENGAN, self::WARNASARI]],
        ];
    }

    public function test_each_recipient_gets_a_separate_email_with_correct_audience(): void
    {
        $this->post(route('pengaduan.store'), $this->payload());

        Mail::assertSent(NewComplaintMail::class, fn ($m) => $m->hasTo(self::WARNASARI) && $m->audience === 'puskesmas' && count($m->to) === 1);
        Mail::assertSent(NewComplaintMail::class, fn ($m) => $m->hasTo(self::CAMAT) && $m->audience === 'pantau' && count($m->to) === 1);
        Mail::assertSent(NewComplaintMail::class, fn ($m) => $m->hasTo(self::ADMIN) && $m->audience === 'pantau' && count($m->to) === 1);
    }

    public function test_email_contains_ticket_but_never_reporter_personal_data(): void
    {
        $this->post(route('pengaduan.store'), $this->payload());
        $complaint = Complaint::firstOrFail();

        $mail = Mail::sent(NewComplaintMail::class)->first();
        $html = $mail->render();

        $this->assertStringContainsString($complaint->ticket_code, $html);
        $this->assertStringContainsString('Posyandu jarang buka', $html);
        $this->assertStringContainsString(route('complaints.show', $complaint), $html);
        $this->assertStringNotContainsString('Warga Rahasia', $html);
        $this->assertStringNotContainsString('081299998888', $html);
        $this->assertStringContainsString($complaint->ticket_code, $mail->envelope()->subject);
    }

    public function test_email_escapes_html_in_user_input(): void
    {
        $this->post(route('pengaduan.store'), $this->payload([
            'judul' => '<script>alert(1)</script> judul',
        ]));

        $html = Mail::sent(NewComplaintMail::class)->first()->render();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
    }

    public function test_no_email_for_invalid_or_bot_submissions(): void
    {
        $this->post(route('pengaduan.store'), $this->payload(['isi' => 'pendek']))->assertSessionHasErrors('isi');
        $this->post(route('pengaduan.store'), $this->payload(['website' => 'http://spam.test']));

        Mail::assertNothingSent();
        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_missing_puskesmas_email_still_notifies_camat_and_admin(): void
    {
        config(['pengaduan.notify.puskesmas.WARNASARI' => null]);

        $this->post(route('pengaduan.store'), $this->payload())->assertSessionHasNoErrors();

        $this->assertSame(
            collect([self::CAMAT, self::ADMIN])->sort()->values()->all(),
            $this->recipients()
        );
    }

    public function test_duplicate_addresses_receive_only_one_email(): void
    {
        config(['pengaduan.notify.admin' => strtoupper(self::WARNASARI)]);

        $this->post(route('pengaduan.store'), $this->payload());

        Mail::assertSent(NewComplaintMail::class, 2); // warnasari/admin sama + camat
    }

    public function test_mail_failure_never_breaks_complaint_submission(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $response = $this->post(route('pengaduan.store'), $this->payload());

        $complaint = Complaint::firstOrFail();
        $response->assertRedirect(route('pengaduan.track', ['kode' => $complaint->ticket_code]));
    }
}
