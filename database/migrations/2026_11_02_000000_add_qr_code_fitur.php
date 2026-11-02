<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('fiturs')->insertOrIgnore([
            'nama' => 'QR Code',
            'resource' => 'QR Code',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('fiturs')->where('resource', 'QR Code')->delete();
    }
};
