<?php

namespace App\Http\Controllers\HmifForms;

use App\Http\Controllers\Controller;
use App\Models\HmifForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Support\HmifFormFileUpload;

class PublicFormController extends Controller
{
    private function published(string $token): HmifForm
    {
        return HmifForm::query()->where('public_token', $token)->whereIn('status', ['published', 'closed'])->with(['sections.questions.options', 'questions.options'])->firstOrFail();
    }

    public function show(Request $request, string $token): View|RedirectResponse
    {
        $form = $this->published($token);
        $unavailableMessage = $this->unavailableMessage($form);
        if ($form->require_login && ! $request->user()) {
            return redirect()->guest('/admin/login');
        }
        if ($form->shuffle_questions && ! $unavailableMessage) {
            foreach ($form->sections as $section) {
                $section->setRelation('questions', $section->questions->shuffle()->values());
            }
            $form->setRelation('questions', $form->questions->shuffle()->values());
        }

        return view('hmif-forms.public', compact('form', 'unavailableMessage'));
    }

    public function submit(Request $request, string $token): RedirectResponse
    {
        $form = $this->published($token);
        abort_if($this->unavailableMessage($form), 422, $this->unavailableMessage($form));
        if ($form->require_login && ! $request->user()) {
            return redirect()->guest('/admin/login');
        }
        $rules = $form->collect_email || $form->limit_one_response ? ['respondent_email' => [$request->user() ? 'nullable' : 'required', 'email', 'max:255']] : [];
        $respondentSectionIds = $this->respondentSectionIds($form);
        foreach ($form->questions as $question) {
            $isRequired = $question->is_required && $respondentSectionIds->contains($question->section_id);
            if ($question->type === 'file_upload') {
                $settings = HmifFormFileUpload::normalize($question->settings);
                $rules["answers.{$question->id}"] = [$isRequired ? 'required' : 'nullable', 'array', 'max:'.$settings['max_files']];
                $rules["answers.{$question->id}.*"] = ['file', 'max:'.($settings['max_file_size_mb'] * 1024), 'mimetypes:'.implode(',', HmifFormFileUpload::allowedMimes($settings))];
            } else {
                $rules["answers.{$question->id}"] = [$isRequired ? 'required' : 'nullable', $question->type === 'checkbox' ? 'array' : 'string'];
            }
        }
        $validated = $request->validate($rules);
        $email = $request->user()?->email ?: ($validated['respondent_email'] ?? null);
        if ($form->limit_one_response) {
            $alreadySubmitted = $request->user()
                ? $form->responses()->where('respondent_id', $request->user()->id)->exists()
                : $form->responses()->where('respondent_email', $email)->exists();
            abort_if($alreadySubmitted, 422, 'Anda sudah mengirim response untuk form ini.');
        }
        $storedFiles = [];
        try {
            DB::transaction(function () use ($request, $form, $validated, $email, &$storedFiles) {
                $response = $form->responses()->create(['respondent_id' => $request->user()?->id, 'respondent_email' => $form->collect_email || $form->limit_one_response ? $email : null, 'submitted_at' => now()]);
                foreach ($form->questions as $question) {
                    $value = data_get($validated, "answers.{$question->id}");
                    $response->answers()->create(['question_id' => $question->id, 'value' => $question->type === 'file_upload' ? null : (is_array($value) ? json_encode($value) : $value)]);
                    if ($question->type !== 'file_upload') continue;
                    foreach ((array) $value as $file) {
                        $extension = $file->guessExtension() ?: 'bin';
                        $path = 'hmif-forms/responses/'.$form->uuid.'/'.$response->id.'/'.Str::uuid().'.'.$extension;
                        Storage::disk('local')->putFileAs(dirname($path), $file, basename($path));
                        $storedFiles[] = ['disk' => 'local', 'path' => $path];
                        $response->files()->create(['question_id' => $question->id, 'disk' => 'local', 'path' => $path, 'original_name' => basename($file->getClientOriginalName()), 'mime_type' => $file->getMimeType() ?: 'application/octet-stream', 'size' => $file->getSize()]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($storedFiles as $storedFile) Storage::disk($storedFile['disk'])->delete($storedFile['path']);
            throw $exception;
        }

        return redirect()->route('hmif-forms.public.show', $token)->with('submitted', true)->with('submitted_message', $form->confirmation_message ?: 'Respons Anda telah direkam.');
    }

    private function unavailableMessage(HmifForm $form): ?string
    {
        if ($form->status === 'closed') return 'Form ini sudah tidak menerima response.';
        if ($form->opens_at?->isFuture()) return 'Form belum dibuka.';
        if ($form->closes_at?->isPast()) return 'Form sudah ditutup.';

        return null;
    }

    private function respondentSectionIds(HmifForm $form): \Illuminate\Support\Collection
    {
        $sections = $form->sections->sortBy('position')->values();
        $indexes = $sections->pluck('id')->mapWithKeys(fn ($id, $index) => [$id => $index]);
        $visited = collect();
        $index = 0;

        while ($sections->has($index) && ! $visited->contains($sections[$index]->id)) {
            $section = $sections[$index];
            $visited->push($section->id);
            if ($section->after_section_action === 'submit') break;
            if ($section->after_section_action === 'go_to_section') {
                $target = $indexes->get($section->after_section_target_id);
                if ($target === null) break;
                $index = $target;
                continue;
            }
            $index++;
        }

        return $visited;
    }
}
