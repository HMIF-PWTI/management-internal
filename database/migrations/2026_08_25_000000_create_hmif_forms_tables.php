<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hmif_forms', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->default('Untitled form');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft')->index();
            $table->string('public_token', 64)->nullable()->unique();
            $table->boolean('require_login')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['owner_id', 'updated_at']);
        });

        Schema::create('hmif_form_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('hmif_forms')->cascadeOnDelete();
            $table->string('type', 32)->default('short_text');
            $table->text('label');
            $table->text('description')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['form_id', 'sort_order']);
        });

        Schema::create('hmif_form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('hmif_forms')->cascadeOnDelete();
            $table->foreignId('respondent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('respondent_email')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->index(['form_id', 'submitted_at']);
        });

        Schema::create('hmif_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('hmif_form_responses')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('hmif_form_questions')->cascadeOnDelete();
            $table->longText('value')->nullable();
            $table->timestamps();
            $table->unique(['response_id', 'question_id']);
        });

        DB::table('fiturs')->insertOrIgnore([
            'nama' => 'HMIF Forms',
            'resource' => 'HMIF Forms',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hmif_form_answers');
        Schema::dropIfExists('hmif_form_responses');
        Schema::dropIfExists('hmif_form_questions');
        Schema::dropIfExists('hmif_forms');
    }
};
