<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Membuat akun superadmin bawaan.
     */
    public function run(): void
    {
        $superadminDivision = Divisi::updateOrCreate(
            ['singkatan' => 'superadmin'],
            [
                'nama' => 'Super Administrator',
                'deskripsi' => 'Akses penuh ke seluruh sistem',
            ]
        );

        User::updateOrCreate(
            ['email' => 'superadmin@hmif.com'],
            [
                'nama' => 'Super Admin',
                'nim' => 'SUPERADMIN',
                'password' => Hash::make('Superadmin123!'),
                'id_divisi' => $superadminDivision->id,
                'status_keanggotaan' => 'aktif',
            ]
        );
    }
}
