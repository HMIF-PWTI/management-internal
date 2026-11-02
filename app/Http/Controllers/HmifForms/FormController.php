<?php

namespace App\Http\Controllers\HmifForms;

use App\Filament\Pages\HmifFormBuilder;
use App\Filament\Pages\MyHmifForms;
use App\Http\Controllers\Controller;
use App\Models\HmifForm;
use App\Models\HmifFormQuestion;
use App\Services\HmifFormTemplateService;
use App\Support\HmifFormTheme;
use App\Support\HmifFormFileUpload;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormController extends Controller
{
    public function saveBuilder(Request $request, HmifForm $form): RedirectResponse
    {
        $form = $this->owned($request, $form);
        $payload = $request->validate([
            'builder' => ['required', 'json'],
            'intent' => ['nullable', 'in:save,publish'],
            'theme_header_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $builder = json_decode($payload['builder'], true, flags: JSON_THROW_ON_ERROR);
        $builder = Validator::make($builder, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'require_login' => ['required', 'boolean'],
            'settings' => ['required', 'array'],
            'settings.accepting_responses' => ['required', 'boolean'],
            'settings.collect_email' => ['required', 'boolean'],
            'settings.limit_one_response' => ['required', 'boolean'],
            'settings.show_progress_bar' => ['required', 'boolean'],
            'settings.shuffle_questions' => ['required', 'boolean'],
            'settings.required_by_default' => ['required', 'boolean'],
            'settings.confirmation_message' => ['nullable', 'string', 'max:5000'],
            'settings.opens_at' => ['nullable', 'date'],
            'settings.closes_at' => ['nullable', 'date'],
            'theme' => ['required', 'array'],
            'theme.header_font' => ['required', Rule::in(HmifFormTheme::FONTS)],
            'theme.header_size' => ['required', 'integer', Rule::in(HmifFormTheme::HEADER_SIZES)],
            'theme.question_font' => ['required', Rule::in(HmifFormTheme::FONTS)],
            'theme.question_size' => ['required', 'integer', Rule::in(HmifFormTheme::QUESTION_SIZES)],
            'theme.text_font' => ['required', Rule::in(HmifFormTheme::FONTS)],
            'theme.text_size' => ['required', 'integer', Rule::in(HmifFormTheme::TEXT_SIZES)],
            'theme.primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme.background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme.surface_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme.header_image' => ['nullable', 'string', 'max:500'],
            'theme.remove_header_image' => ['required', 'boolean'],
            'sections' => ['required', 'array', 'min:1', 'max:100'],
            'sections.*.id' => ['nullable', 'integer'],
            'sections.*.client_id' => ['required', 'string', 'max:100'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.description' => ['nullable', 'string', 'max:2000'],
            'sections.*.after_section_action' => ['required', 'in:continue_next,go_to_section,submit'],
            'sections.*.after_section_target' => ['nullable', 'string', 'max:100'],
            'questions' => ['present', 'array', 'max:200'],
            'deleted_question_ids' => ['present', 'array'],
            'deleted_question_ids.*' => ['integer'],
            'deleted_option_ids' => ['present', 'array'],
            'deleted_option_ids.*' => ['integer'],
            'questions.*.id' => ['nullable', 'integer'],
            'questions.*.client_id' => ['required', 'string', 'max:100'],
            'questions.*.section_client_id' => ['required', 'string', 'max:100'],
            'questions.*.label' => ['required', 'string', 'max:1000'],
            'questions.*.description' => ['nullable', 'string', 'max:2000'],
            'questions.*.type' => ['required', 'in:short_text,paragraph,email,number,date,radio,checkbox,select,file_upload'],
            'questions.*.required' => ['required', 'boolean'],
            'questions.*.settings' => ['nullable', 'array'],
            'questions.*.settings.max_files' => ['nullable', 'integer', Rule::in([1, 2, 3, 5, 10])],
            'questions.*.settings.max_file_size_mb' => ['nullable', 'integer', Rule::in(HmifFormFileUpload::sizeOptions())],
            'questions.*.settings.allowed_types_enabled' => ['nullable', 'boolean'],
            'questions.*.settings.allowed_types' => ['nullable', 'array'],
            'questions.*.settings.allowed_types.*' => [Rule::in(array_keys(HmifFormFileUpload::TYPE_MIMES))],
            'questions.*.options' => ['present', 'array', 'max:100'],
            'questions.*.options.*.id' => ['nullable', 'integer'],
            'questions.*.options.*.client_id' => ['required', 'string', 'max:100'],
            'questions.*.options.*.label' => ['required', 'string', 'max:255'],
        ])->after(function ($validator) use ($builder, $payload): void {
            if (($payload['intent'] ?? null) === 'publish' && empty($builder['questions'])) {
                $validator->errors()->add('questions', 'Tambahkan minimal satu pertanyaan sebelum publish.');
            }
            if (filled(data_get($builder, 'settings.opens_at')) && filled(data_get($builder, 'settings.closes_at')) && \Illuminate\Support\Carbon::parse(data_get($builder, 'settings.closes_at'))->lte(\Illuminate\Support\Carbon::parse(data_get($builder, 'settings.opens_at')))) {
                $validator->errors()->add('settings.closes_at', 'Waktu tutup harus setelah waktu buka.');
            }
            foreach ($builder['questions'] ?? [] as $index => $question) {
                if (in_array($question['type'] ?? null, ['radio', 'checkbox', 'select'], true) && empty($question['options'])) {
                    $validator->errors()->add("questions.{$index}.options", 'Tambahkan minimal satu opsi.');
                }
                if (($question['type'] ?? null) === 'file_upload' && data_get($question, 'settings.allowed_types_enabled') && empty(data_get($question, 'settings.allowed_types', []))) {
                    $validator->errors()->add("questions.{$index}.settings.allowed_types", 'Pilih minimal satu tipe file.');
                }
            }
            $sectionClientIds = collect($builder['sections'] ?? [])->pluck('client_id');
            foreach ($builder['sections'] ?? [] as $index => $section) {
                if (($section['after_section_action'] ?? null) !== 'go_to_section') continue;
                $target = $section['after_section_target'] ?? null;
                if (! $target || ! $sectionClientIds->contains($target) || $target === ($section['client_id'] ?? null)) {
                    $validator->errors()->add("sections.{$index}.after_section_target", 'Target section tidak valid.');
                }
            }
        })->validate();

        $oldHeaderImage = data_get($form->theme_settings, 'header_image');
        $newHeaderImage = $request->file('theme_header_image')?->store('hmif-forms/headers', 'public');
        $theme = HmifFormTheme::normalize($builder['theme']);
        $theme['header_image'] = $oldHeaderImage;
        if ($builder['theme']['remove_header_image']) $theme['header_image'] = null;
        if ($newHeaderImage) $theme['header_image'] = $newHeaderImage;

        try {
            DB::transaction(function () use ($form, $builder, $payload, $theme): void {
                $existingSections = $form->sections()->get()->keyBy('id');
                $submittedSectionIds = collect($builder['sections'])->pluck('id')->filter()->map(fn ($id) => (int) $id);
                abort_if($submittedSectionIds->diff($existingSections->keys())->isNotEmpty(), 422, 'Section tidak valid.');

                $sectionsByClientId = collect();
                foreach ($builder['sections'] as $sectionPosition => $sectionData) {
                    $section = filled($sectionData['id'] ?? null)
                        ? $existingSections->get((int) $sectionData['id'])
                        : $form->sections()->make();
                    abort_unless($section, 422, 'Section tidak valid.');
                    $section->fill([
                        'title' => $sectionPosition === 0 ? null : ($sectionData['title'] ?: null),
                        'description' => $sectionPosition === 0 ? null : ($sectionData['description'] ?: null),
                        'position' => $sectionPosition,
                        'after_section_action' => $sectionData['after_section_action'],
                        'after_section_target_id' => null,
                    ]);
                    $section->form()->associate($form);
                    $section->save();
                    $sectionsByClientId->put($sectionData['client_id'], $section);
                }

                foreach ($builder['sections'] as $sectionData) {
                    $section = $sectionsByClientId->get($sectionData['client_id']);
                    $target = $sectionsByClientId->get($sectionData['after_section_target'] ?? null);
                    abort_if($sectionData['after_section_action'] === 'go_to_section' && (! $target || $target->is($section)), 422, 'Target section tidak valid.');
                    $section->update([
                        'after_section_action' => $sectionData['after_section_action'],
                        'after_section_target_id' => $sectionData['after_section_action'] === 'go_to_section' ? $target->id : null,
                    ]);
                }

                $existingQuestions = $form->questions()->with('options')->get()->keyBy('id');
                $submittedQuestionIds = collect($builder['questions'])->pluck('id')->filter()->map(fn ($id) => (int) $id);

                abort_if($submittedQuestionIds->diff($existingQuestions->keys())->isNotEmpty(), 422, 'Pertanyaan tidak valid.');
                $form->questions()->whereNotIn('id', $submittedQuestionIds)->delete();

                $form->update([
                    'title' => $builder['title'],
                    'description' => $builder['description'] ?? null,
                    'require_login' => $builder['require_login'],
                    'collect_email' => $builder['settings']['collect_email'],
                    'limit_one_response' => $builder['settings']['limit_one_response'],
                    'show_progress_bar' => $builder['settings']['show_progress_bar'],
                    'shuffle_questions' => $builder['settings']['shuffle_questions'],
                    'required_by_default' => $builder['settings']['required_by_default'],
                    'confirmation_message' => $builder['settings']['confirmation_message'] ?: null,
                    'opens_at' => $builder['settings']['opens_at'] ?: null,
                    'closes_at' => $builder['settings']['closes_at'] ?: null,
                    'theme_settings' => $theme,
                ]);

                if (! $builder['settings']['accepting_responses'] && $form->status === 'published') {
                    $form->update(['status' => 'closed']);
                } elseif ($builder['settings']['accepting_responses'] && $form->status === 'closed') {
                    $form->update(['status' => 'published']);
                }

                foreach ($builder['questions'] as $position => $questionData) {
                    $question = filled($questionData['id'] ?? null)
                        ? $existingQuestions->get((int) $questionData['id'])
                        : $form->questions()->make();

                    abort_unless($question, 422, 'Pertanyaan tidak valid.');
                    $question->fill([
                        'section_id' => $sectionsByClientId->get($questionData['section_client_id'])?->id,
                        'type' => $questionData['type'],
                        'label' => $questionData['label'],
                        'description' => $questionData['description'] ?? null,
                        'settings' => $questionData['type'] === 'file_upload' ? HmifFormFileUpload::normalize($questionData['settings']) : null,
                        'is_required' => $questionData['required'],
                        'sort_order' => $position,
                    ]);
                    abort_unless($question->section_id, 422, 'Section pertanyaan tidak valid.');
                    $question->form()->associate($form);
                    $question->save();

                    $existingOptions = $question->options()->get()->keyBy('id');
                    $optionData = in_array($questionData['type'], ['radio', 'checkbox', 'select'], true) ? $questionData['options'] : [];
                    $submittedOptionIds = collect($optionData)->pluck('id')->filter()->map(fn ($id) => (int) $id);
                    abort_if($submittedOptionIds->diff($existingOptions->keys())->isNotEmpty(), 422, 'Opsi tidak valid.');
                    $question->options()->whereNotIn('id', $submittedOptionIds)->delete();

                    foreach ($optionData as $optionPosition => $option) {
                        $record = filled($option['id'] ?? null)
                            ? $existingOptions->get((int) $option['id'])
                            : $question->options()->make();
                        abort_unless($record, 422, 'Opsi tidak valid.');
                        $record->fill([
                            'label' => $option['label'],
                            'value' => $option['value'] ?? null,
                            'position' => $optionPosition,
                            'is_other' => (bool) ($option['is_other'] ?? false),
                        ]);
                        $record->question()->associate($question);
                        $record->save();
                    }
                }

                // Runs after the question loop: the FK cascades, so a question moved out of a
                // removed section would otherwise be deleted before its new section is saved.
                $form->sections()->whereNotIn('id', $sectionsByClientId->pluck('id'))->delete();

                if (($payload['intent'] ?? 'save') === 'publish') {
                    $form->update([
                        'status' => 'published',
                        'public_token' => $form->public_token ?: Str::random(32),
                        'published_at' => now(),
                    ]);
                }
            });

            Notification::make()->title(($payload['intent'] ?? 'save') === 'publish' ? 'Form berhasil dipublikasikan.' : 'Form berhasil disimpan.')->success()->send();
        } catch (\Throwable $exception) {
            if ($newHeaderImage) Storage::disk('public')->delete($newHeaderImage);
            report($exception);
            Notification::make()->title('Gagal menyimpan perubahan.')->danger()->send();

            return back()->withInput();
        }

        return back();
    }

    private function authorizeAccess(Request $request): void
    {
        abort_unless($request->user()?->canAccessFitur('HMIF Forms'), 403);
    }

    private function owned(Request $request, HmifForm $form): HmifForm
    {
        $this->authorizeAccess($request);
        abort_unless($form->owner_id === $request->user()->id, 403);

        return $form;
    }

    public function store(Request $request, HmifFormTemplateService $templates): RedirectResponse
    {
        $this->authorizeAccess($request);
        abort_unless($request->user()->hasPermission('HMIF Forms', 'create') || $request->user()->canAccessFitur('HMIF Forms'), 403);
        $template = $request->validate(['template' => 'nullable|string'])['template'] ?? 'blank';
        $form = $templates->create($request->user(), $template);

        return redirect(HmifFormBuilder::getUrl(['form' => $form->uuid]));
    }

    public function update(Request $request, HmifForm $form): RedirectResponse
    {
        $form = $this->owned($request, $form);
        $data = $request->validate(['title' => 'required|string|max:255', 'description' => 'nullable|string|max:5000', 'require_login' => 'nullable|boolean']);
        $form->update([...$data, 'require_login' => $request->boolean('require_login')]);

        return back()->with('success', 'Form berhasil disimpan.');
    }

    public function addQuestion(Request $request, HmifForm $form): RedirectResponse
    {
        $form = $this->owned($request, $form);
        [$data, $options] = $this->validatedQuestion($request);

        DB::transaction(function () use ($form, $data, $options, $request): void {
            $section = $form->sections()->firstOrCreate([], ['title' => null, 'position' => 0, 'after_section_action' => 'submit']);
            $question = $form->questions()->create([
                ...$data,
                'section_id' => $section->id,
                'is_required' => $request->boolean('is_required'),
                'sort_order' => ($form->questions()->max('sort_order') ?? -1) + 1,
            ]);
            $question->options()->createMany($this->optionRows($options));
        });

        return back()->with('success', 'Pertanyaan ditambahkan.');
    }

    public function updateQuestion(Request $request, HmifForm $form, HmifFormQuestion $question): RedirectResponse
    {
        $form = $this->owned($request, $form);
        abort_unless($question->form_id === $form->id, 404);
        [$data, $options] = $this->validatedQuestion($request);

        DB::transaction(function () use ($question, $data, $options, $request): void {
            $question->update([
                ...$data,
                'is_required' => $request->boolean('is_required'),
            ]);
            $question->options()->delete();
            $question->options()->createMany($this->optionRows($options));
        });

        return back()->with('success', 'Pertanyaan diperbarui.');
    }

    private function validatedQuestion(Request $request): array
    {
        $data = $request->validate([
            'label' => 'required|string|max:1000',
            'type' => 'required|in:short_text,paragraph,email,number,date,radio,checkbox,select,file_upload',
            'options' => 'nullable|array|max:100',
            'options.*' => 'nullable|string|max:255',
            'is_required' => 'nullable|boolean',
        ]);
        $needsOptions = in_array($data['type'], ['radio', 'checkbox', 'select'], true);
        $options = $needsOptions
            ? collect($data['options'] ?? [])->map(fn ($option) => trim((string) $option))->filter()->unique(fn ($option) => mb_strtolower($option))->values()->all()
            : null;

        if ($needsOptions && empty($options)) {
            throw ValidationException::withMessages(['options' => 'Tambahkan minimal satu opsi yang tidak kosong.']);
        }

        unset($data['options'], $data['is_required']);

        return [$data, $options];
    }

    private function optionRows(?array $options): array
    {
        return collect($options ?? [])->values()->map(fn (string $label, int $position): array => [
            'label' => $label,
            'value' => null,
            'position' => $position,
            'is_other' => in_array(mb_strtolower($label), ['lainnya', 'other'], true),
        ])->all();
    }

    public function deleteQuestion(Request $request, HmifForm $form, HmifFormQuestion $question): RedirectResponse
    {
        $form = $this->owned($request, $form);
        abort_unless($question->form_id === $form->id, 404);
        $question->delete();
        Notification::make()->title('Pertanyaan berhasil dihapus.')->success()->send();

        return back();
    }

    public function duplicateQuestion(Request $request, HmifForm $form, HmifFormQuestion $question): RedirectResponse
    {
        $form = $this->owned($request, $form);
        abort_unless($question->form_id === $form->id, 404);
        DB::transaction(function () use ($form, $question): void {
            $question->loadMissing('options');
            $copy = $form->questions()->create([
                ...$question->only(['section_id', 'type', 'label', 'description', 'settings', 'is_required']),
                'sort_order' => ($form->questions()->max('sort_order') ?? -1) + 1,
            ]);
            $copy->options()->createMany($question->options->map->only(['label', 'value', 'position', 'is_other'])->all());
        });

        return back()->with('success', 'Pertanyaan diduplikasi.');
    }

    public function publish(Request $request, HmifForm $form): RedirectResponse
    {
        $form = $this->owned($request, $form);
        $form->update(['status' => 'published', 'public_token' => $form->public_token ?: Str::random(32), 'published_at' => now()]);
        Notification::make()->title('Form berhasil dipublikasikan.')->success()->send();

        return back();
    }

    public function unpublish(Request $request, HmifForm $form): RedirectResponse
    {
        $form = $this->owned($request, $form);
        $form->update(['status' => 'draft', 'published_at' => null]);
        Notification::make()->title('Form berhasil ditutup dari publik.')->success()->send();

        return back();
    }

    public function duplicate(Request $request, HmifForm $form): RedirectResponse
    {
        $returnTo = $request->validate(['return_to' => ['nullable', 'in:dashboard']])['return_to'] ?? null;
        $source = $this->owned($request, $form)->load(['sections', 'questions.options']);
        $copy = DB::transaction(function () use ($request, $source) {
            $copy = $request->user()->hmifForms()->create(['title' => $source->title.' (Copy)', 'description' => $source->description, 'status' => 'draft', ...$source->only(['require_login', 'collect_email', 'limit_one_response', 'show_progress_bar', 'shuffle_questions', 'required_by_default', 'confirmation_message', 'theme_settings'])]);
            $sectionMap = [];
            foreach ($source->sections as $section) {
                $sectionMap[$section->id] = $copy->sections()->create($section->only(['title', 'description', 'position', 'after_section_action']))->id;
            }
            if (empty($sectionMap)) $sectionMap[0] = $copy->sections()->create(['title' => null, 'position' => 0, 'after_section_action' => 'submit'])->id;
            foreach ($source->sections as $section) {
                if ($section->after_section_action === 'go_to_section' && isset($sectionMap[$section->after_section_target_id])) {
                    $copy->sections()->whereKey($sectionMap[$section->id])->update(['after_section_target_id' => $sectionMap[$section->after_section_target_id]]);
                }
            }
            foreach ($source->questions as $question) {
                $questionCopy = $copy->questions()->create([...$question->only(['type', 'label', 'description', 'settings', 'is_required', 'sort_order']), 'section_id' => $sectionMap[$question->section_id] ?? reset($sectionMap)]);
                $questionCopy->options()->createMany($question->options->map->only(['label', 'value', 'position', 'is_other'])->all());
            }

            return $copy;
        });

        Notification::make()->title('Form berhasil diduplikasi.')->success()->send();

        return $returnTo === 'dashboard'
            ? back()
            : redirect(HmifFormBuilder::getUrl(['form' => $copy->uuid]));
    }

    public function exportResponses(Request $request, HmifForm $form): StreamedResponse
    {
        $form = $this->owned($request, $form);
        $questions = $form->questions()->get();
        // Respondents are public, so a leading = + - @ must not run as a spreadsheet formula.
        $cell = fn (?string $value): string => preg_match('/^[=+\-@\t\r]/', (string) $value) ? "'".$value : (string) $value;

        return response()->streamDownload(function () use ($form, $questions, $cell): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Waktu', 'Email', ...$questions->pluck('label')->map($cell)]);

            foreach ($form->responses()->with(['answers.question', 'files'])->oldest('submitted_at')->cursor() as $response) {
                $answers = $response->answers->keyBy('question_id');
                $files = $response->files->groupBy('question_id');

                fputcsv($output, [
                    $response->submitted_at->format('Y-m-d H:i:s'),
                    $cell($response->respondent_email),
                    ...$questions->map(fn ($question) => $cell($question->type === 'file_upload'
                        ? $files->get($question->id)?->pluck('original_name')->implode(', ')
                        : $answers->get($question->id)?->displayValue())),
                ]);
            }

            fclose($output);
        }, Str::slug($form->title).'-responses.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function destroy(Request $request, HmifForm $form): RedirectResponse
    {
        $form = $this->owned($request, $form);

        try {
            $form->delete();
            Notification::make()->title('Form berhasil dihapus.')->success()->send();
        } catch (\Throwable $exception) {
            report($exception);
            Notification::make()->title('Form gagal dihapus.')->danger()->send();
        }

        return redirect(MyHmifForms::getUrl());
    }
}
