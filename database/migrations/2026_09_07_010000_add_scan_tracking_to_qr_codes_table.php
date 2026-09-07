<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->boolean('scan_tracking')->default(true)->after('shape');
            $table->unsignedBigInteger('total_scans')->default(0)->after('scan_tracking');
            $table->unsignedBigInteger('unique_scans')->default(0)->after('total_scans');
            $table->string('top_location')->nullable()->after('unique_scans');
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropColumn(['scan_tracking', 'total_scans', 'unique_scans', 'top_location']);
        });
    }
};
