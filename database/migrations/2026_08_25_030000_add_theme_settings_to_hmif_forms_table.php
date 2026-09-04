<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hmif_forms', function (Blueprint $table): void {
            $table->json('theme_settings')->nullable()->after('closes_at');
        });
    }

    public function down(): void
    {
        Schema::table('hmif_forms', function (Blueprint $table): void {
            $table->dropColumn('theme_settings');
        });
    }
};
