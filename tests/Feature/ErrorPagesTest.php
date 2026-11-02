<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    public static function errorPages(): array
    {
        return [
            [401, 'Perlu Login'],
            [402, 'Pembayaran Diperlukan'],
            [403, 'Akses Ditolak'],
            [404, 'Halaman Tidak Ditemukan'],
            [405, 'Metode Tidak Diizinkan'],
            [418, 'Permintaan Tidak Dapat Diproses'],
            [419, 'Sesi Berakhir'],
            [422, 'Data Tidak Valid'],
            [429, 'Terlalu Banyak Permintaan'],
            [500, 'Terjadi Kesalahan Server'],
            [502, 'Layanan Sedang Bermasalah'],
            [503, 'Sedang Dalam Pemeliharaan'],
        ];
    }

    #[DataProvider('errorPages')]
    public function test_error_code_renders_its_custom_page(int $code, string $title): void
    {
        Route::get('/_error', fn () => abort($code));

        $this->get('/_error')
            ->assertStatus($code)
            ->assertSee("Error {$code}")
            ->assertSee($title);
    }

    public function test_custom_abort_message_is_shown_and_escaped(): void
    {
        Route::get('/_error', fn () => abort(403, '<b>Link expired</b>'));

        $this->get('/_error')
            ->assertSee('&lt;b&gt;Link expired&lt;/b&gt;', false)
            ->assertDontSee('<b>Link expired</b>', false);
    }

    public function test_framework_english_message_is_replaced(): void
    {
        Route::get('/_error', fn () => abort(403, 'This action is unauthorized.'));

        $this->get('/_error')
            ->assertSee('Anda tidak memiliki izin')
            ->assertDontSee('This action is unauthorized.');
    }
}
