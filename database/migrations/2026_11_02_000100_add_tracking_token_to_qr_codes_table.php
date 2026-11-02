<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->string('tracking_token', 26)->nullable()->unique()->after('user_id');
        });

        DB::table('qr_codes')->orderBy('id')->each(function ($qrCode) {
            DB::table('qr_codes')->where('id', $qrCode->id)->update(['tracking_token' => (string) Str::ulid()]);
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropUnique(['tracking_token']);
            $table->dropColumn('tracking_token');
        });
    }
};
