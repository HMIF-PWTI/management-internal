<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hmif_form_sections', function (Blueprint $table): void {
            $table->string('title')->nullable()->default(null)->change();
        });

        DB::table('hmif_form_sections')
            ->where('position', 0)
            ->update(['title' => null, 'description' => null]);
    }

    public function down(): void
    {
        DB::table('hmif_form_sections')->whereNull('title')->update(['title' => 'Section']);
        Schema::table('hmif_form_sections', function (Blueprint $table): void {
            $table->string('title')->nullable(false)->default('Section 1')->change();
        });
    }
};
