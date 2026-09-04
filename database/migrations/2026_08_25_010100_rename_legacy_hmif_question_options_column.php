<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hmif_form_questions', function (Blueprint $table): void {
            $table->renameColumn('options', 'legacy_options');
        });
    }

    public function down(): void
    {
        Schema::table('hmif_form_questions', function (Blueprint $table): void {
            $table->renameColumn('legacy_options', 'options');
        });
    }
};
