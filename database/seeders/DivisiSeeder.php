<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisis = [
            [
                'nama' => 'Super Administrator',
                'singkatan' => 'superadmin',
                'deskripsi' => 'Akses penuh ke seluruh sistem',
            ],
            [
                'nama' => 'Pengembangan Wawasan dan Teknologi Informasi',
                'singkatan' => 'pwti',
                'deskripsi' => 'Divisi Pengembangan Wawasan dan Teknologi Informasi',
            ],
            [
                'nama' => 'Komunikasi dan Informasi ',
                'singkatan' => 'kominfo',
                'deskripsi' => 'Divisi Komunikasi dan Informasi ',
            ],
            [
                'nama' => 'Kesejahteraan Mahasiswa',
                'singkatan' => 'kesma',
                'deskripsi' => 'Divisi Kesejahteraan Mahasiswa',
            ],
            [
                'nama' => 'Kesenian dan Olahraga',
                'singkatan' => 'kesra',
                'deskripsi' => 'Divisi Kesenian dan Olahraga',
            ],
            [
                'nama' => 'Kewirausahaan',
                'singkatan' => 'kwu',
                'deskripsi' => 'Divisi Kewirausahaan',
            ],
            [
                'nama' => 'Pengembangan Aparatur Organisasi',
                'singkatan' => 'pao',
                'deskripsi' => 'Divisi Pengembangan Aparatur Organisasi',
            ],
            [
                'nama' => 'Sosial dan Keagamaan',
                'singkatan' => 'sosma',
                'deskripsi' => 'Divisi Sosial dan Keagamaan',
            ],
        ];

        foreach ($divisis as $divisi) {
            Divisi::updateOrCreate(
                ['singkatan' => $divisi['singkatan']],
                $divisi
            );
        }
    }
}