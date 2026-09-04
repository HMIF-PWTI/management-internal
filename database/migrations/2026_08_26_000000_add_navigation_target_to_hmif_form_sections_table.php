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
            $table->foreignId('after_section_target_id')
                ->nullable()
                ->after('after_section_action')
                ->constrained('hmif_form_sections')
                ->nullOnDelete();
        });

        DB::table('hmif_form_sections')
            ->where('after_section_action', 'continue')
            ->update(['after_section_action' => 'continue_next']);

        DB::table('hmif_forms')->select('id')->orderBy('id')->each(function ($form): void {
            $lastSectionId = DB::table('hmif_form_sections')
                ->where('form_id', $form->id)
                ->orderByDesc('position')
                ->value('id');
            if ($lastSectionId) {
                DB::table('hmif_form_sections')->where('id', $lastSectionId)->where('after_section_action', 'continue_next')->update(['after_section_action' => 'submit']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('hmif_form_sections', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('after_section_target_id');
        });
    }
};
