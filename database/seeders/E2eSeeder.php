<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Fitur;
use App\Models\User;
use Illuminate\Database\Seeder;

class E2eSeeder extends Seeder
{
    /**
     * Akun khusus test e2e. Hanya dipanggil oleh tests/e2e/global-setup.ts.
     */
    public function run(): void
    {
        $superadmin = Divisi::updateOrCreate(['singkatan' => 'superadmin'], ['nama' => 'Super Administrator']);
        $anggota = Divisi::updateOrCreate(['singkatan' => 'e2e'], ['nama' => 'Divisi E2E']);
        $anggota->fiturs()->syncWithoutDetaching(Fitur::where('resource', 'HMIF Forms')->pluck('id'));

        foreach ([['admin', $superadmin], ['anggota', $anggota]] as [$name, $divisi]) {
            User::updateOrCreate(['email' => "{$name}@hmif.test"], [
                'nama' => "E2E {$name}",
                'nim' => "E2E-{$name}",
                'password' => 'e2e-password',
                'id_divisi' => $divisi->id,
                'status_keanggotaan' => 'aktif',
            ]);
        }
    }
}
