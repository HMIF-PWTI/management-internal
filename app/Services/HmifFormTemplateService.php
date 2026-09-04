<?php

namespace App\Services;

use App\Models\HmifForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HmifFormTemplateService
{
    public const TEMPLATES = [
        'blank' => ['name' => 'Blank Form', 'description' => null, 'questions' => []],
        'event-registration' => ['name' => 'Event Registration', 'description' => 'Formulir pendaftaran acara.', 'questions' => [
            ['label' => 'Nama lengkap', 'type' => 'short_text', 'is_required' => true],
            ['label' => 'Email', 'type' => 'email', 'is_required' => true],
            ['label' => 'Instansi', 'type' => 'short_text', 'is_required' => false],
        ]],
        'feedback' => ['name' => 'Feedback', 'description' => 'Bagikan pengalaman dan masukan Anda.', 'questions' => [
            ['label' => 'Seberapa puas Anda?', 'type' => 'radio', 'options' => ['Sangat puas', 'Puas', 'Cukup', 'Kurang puas'], 'is_required' => true],
            ['label' => 'Saran dan masukan', 'type' => 'paragraph', 'is_required' => false],
        ]],
        'survey' => ['name' => 'Survey', 'description' => 'Template survei umum.', 'questions' => [
            ['label' => 'Pilihan Anda', 'type' => 'radio', 'options' => ['Opsi 1', 'Opsi 2'], 'is_required' => true],
            ['label' => 'Alasan', 'type' => 'paragraph', 'is_required' => false],
        ]],
        'quiz' => ['name' => 'Quiz', 'description' => 'Template kuis sederhana.', 'questions' => [
            ['label' => 'Pertanyaan 1', 'type' => 'radio', 'options' => ['Jawaban A', 'Jawaban B'], 'is_required' => true],
        ]],
    ];

    public function create(User $owner, string $template = 'blank'): HmifForm
    {
        $definition = self::TEMPLATES[$template] ?? self::TEMPLATES['blank'];

        return DB::transaction(function () use ($owner, $definition, $template): HmifForm {
            $form = $owner->hmifForms()->create([
                'title' => $template === 'blank' ? 'Untitled form' : $definition['name'],
                'description' => $definition['description'],
                'status' => 'draft',
            ]);
            $section = $form->sections()->create(['title' => null, 'position' => 0, 'after_section_action' => 'submit']);
            foreach ($definition['questions'] as $index => $question) {
                $options = $question['options'] ?? [];
                unset($question['options']);
                $createdQuestion = $form->questions()->create([...$question, 'section_id' => $section->id, 'sort_order' => $index]);
                $createdQuestion->options()->createMany(collect($options)->values()->map(fn (string $label, int $position): array => [
                    'label' => $label,
                    'value' => null,
                    'position' => $position,
                    'is_other' => in_array(mb_strtolower($label), ['lainnya', 'other'], true),
                ])->all());
            }
            return $form;
        });
    }
}
