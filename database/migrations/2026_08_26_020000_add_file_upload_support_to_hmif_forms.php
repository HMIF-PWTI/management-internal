<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hmif_form_questions', function (Blueprint $table): void {
            $table->json('settings')->nullable()->after('description');
        });

        Schema::create('hmif_form_response_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('response_id')->constrained('hmif_form_responses')->cascadeOnDelete();
            $table->foreignId('question_id')->nullable()->constrained('hmif_form_questions')->nullOnDelete();
            $table->string('disk', 50)->default('local');
            $table->string('path', 500);
            $table->string('original_name');
            $table->string('mime_type', 150);
            $table->unsignedBigInteger('size');
            $table->timestamps();
            $table->index(['response_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hmif_form_response_files');
        Schema::table('hmif_form_questions', fn (Blueprint $table) => $table->dropColumn('settings'));
    }
};
