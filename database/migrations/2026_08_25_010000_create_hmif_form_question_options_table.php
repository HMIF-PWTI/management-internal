<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hmif_form_question_options', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('question_id')->constrained('hmif_form_questions')->cascadeOnDelete();
            $table->string('label');
            $table->string('value')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_other')->default(false);
            $table->timestamps();
            $table->index(['question_id', 'position']);
        });

        DB::table('hmif_form_questions')
            ->select(['id', 'options'])
            ->whereNotNull('options')
            ->orderBy('id')
            ->chunkById(100, function ($questions): void {
                foreach ($questions as $question) {
                    $options = json_decode((string) $question->options, true);

                    if (! is_array($options)) {
                        continue;
                    }

                    $now = now();
                    $rows = collect($options)
                        ->map(fn ($label) => trim((string) $label))
                        ->filter()
                        ->values()
                        ->map(fn (string $label, int $position): array => [
                            'question_id' => $question->id,
                            'label' => $label,
                            'value' => null,
                            'position' => $position,
                            'is_other' => in_array(mb_strtolower($label), ['lainnya', 'other'], true),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ])->all();

                    if ($rows !== []) {
                        DB::table('hmif_form_question_options')->insert($rows);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('hmif_form_question_options');
    }
};
