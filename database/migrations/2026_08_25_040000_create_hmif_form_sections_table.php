<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hmif_form_sections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('form_id')->constrained('hmif_forms')->cascadeOnDelete();
            $table->string('title')->default('Section 1');
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->string('after_section_action')->default('continue');
            $table->timestamps();
            $table->index(['form_id', 'position']);
        });

        Schema::table('hmif_form_questions', function (Blueprint $table): void {
            $table->foreignId('section_id')->nullable()->after('form_id')->constrained('hmif_form_sections')->cascadeOnDelete();
        });

        DB::table('hmif_forms')->orderBy('id')->each(function ($form): void {
            $sectionId = DB::table('hmif_form_sections')->insertGetId([
                'form_id' => $form->id, 'title' => 'Section 1', 'description' => null,
                'position' => 0, 'after_section_action' => 'continue',
                'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('hmif_form_questions')->where('form_id', $form->id)->whereNull('section_id')->update(['section_id' => $sectionId]);
        });
    }

    public function down(): void
    {
        Schema::table('hmif_form_questions', fn (Blueprint $table) => $table->dropConstrainedForeignId('section_id'));
        Schema::dropIfExists('hmif_form_sections');
    }
};
