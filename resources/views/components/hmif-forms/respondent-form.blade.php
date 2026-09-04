@props(['form', 'mode' => 'public', 'unavailableMessage' => null])

@php
    $isPreview = $mode === 'preview';
    $theme = \App\Support\HmifFormTheme::normalize($form->theme_settings);
    $themeDark = \App\Support\HmifFormTheme::isDark($theme['background_color']);
    $headerImage = $theme['header_image']
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($theme['header_image'])
        : null;
    $hasRequired = $form->questions->contains('is_required', true);
    $respondentSections = $form->sections->sortBy('position')->values()->map(fn ($section) => [
        'id' => $section->id,
        'title' => $section->title,
        'description' => $section->description,
        'after_section_action' => $section->after_section_action === 'continue' ? 'continue_next' : $section->after_section_action,
        'after_section_target_id' => $section->after_section_target_id,
        'questions' => $section->questions->sortBy('sort_order')->values(),
    ]);

    if ($respondentSections->isEmpty()) {
        $respondentSections = collect([[
            'id' => null,
            'title' => 'Section 1',
            'description' => null,
            'after_section_action' => 'submit',
            'after_section_target_id' => null,
            'questions' => $form->questions->sortBy('sort_order')->values(),
        ]]);
    }

    $sectionCount = $respondentSections->count();
    $sectionIndexesById = $respondentSections->pluck('id')->mapWithKeys(fn ($id, $index) => [$id => $index]);
@endphp

<style>
.hmif-respondent-shell,.hmif-respondent-shell *,.hmif-respondent-shell *:before,.hmif-respondent-shell *:after{box-sizing:border-box}.hmif-respondent-shell :is(form,h1,h2,p){margin-block-start:0}.hmif-respondent-shell :is(button,input,textarea,select){font:inherit}.hmif-respondent-shell button{appearance:none}.hmif-respondent-shell .hmif-respondent-option input{appearance:auto}.hmif-respondent-shell{min-height:100vh;background:var(--hmif-background);color:var(--hmif-text);font-family:var(--hmif-text-font);font-size:var(--hmif-text-size);padding:36px 0 64px}.hmif-respondent-canvas{width:calc(100% - 32px);max-width:600px;margin:auto}.hmif-respondent-stack,.hmif-respondent-page{display:flex;flex-direction:column;gap:14px}.hmif-respondent-page[hidden]{display:none!important}.hmif-respondent-banner{display:block;width:100%;max-height:180px;object-fit:contain;border-radius:10px;background:var(--hmif-surface)}.hmif-respondent-card{width:100%;border:1px solid color-mix(in srgb,var(--hmif-text) 14%,transparent);border-radius:10px;background:var(--hmif-surface);padding:18px 20px;box-shadow:0 1px 2px rgb(0 0 0/.08)}.hmif-respondent-header{position:relative;overflow:hidden;padding:26px 22px 20px}.hmif-respondent-header:before{position:absolute;inset:0 0 auto;height:10px;background:var(--hmif-primary);content:""}.hmif-respondent-title{margin:0;font-family:var(--hmif-header-font);font-size:var(--hmif-header-size);line-height:1.2}.hmif-respondent-section-title{margin:0;font-family:var(--hmif-header-font);font-size:calc(var(--hmif-header-size)*.72)}.hmif-respondent-description{margin:12px 0 0;opacity:.78}.hmif-respondent-required-note{margin:18px 0 0;color:#ef4444}.hmif-respondent-question{display:block;margin:0 0 14px;font-family:var(--hmif-question-font);font-size:var(--hmif-question-size);font-weight:600}.hmif-respondent-required,.hmif-respondent-error{color:#ef4444}.hmif-respondent-control{display:block;width:100%;border:1px solid color-mix(in srgb,var(--hmif-text) 26%,transparent);border-radius:6px;background:color-mix(in srgb,var(--hmif-surface) 92%,var(--hmif-text));color:var(--hmif-text);outline:none}.hmif-respondent-control--single{height:40px;min-height:40px;max-height:40px;padding:0 12px}.hmif-respondent-control--multiline{min-height:110px;padding:12px 13px;resize:vertical}.hmif-respondent-control:focus{border-color:var(--hmif-primary);box-shadow:0 0 0 3px color-mix(in srgb,var(--hmif-primary) 22%,transparent)}.hmif-respondent-option{display:flex;align-items:center;gap:10px;margin:11px 0}.hmif-respondent-option input{width:17px;height:17px;accent-color:var(--hmif-primary)}.hmif-respondent-actions{display:flex;align-items:center;justify-content:space-between;gap:12px}.hmif-respondent-action-group{display:flex;gap:10px}.hmif-respondent-submit,.hmif-respondent-next,.hmif-respondent-previous{border-radius:7px;padding:10px 20px;font-weight:700;cursor:pointer}.hmif-respondent-submit,.hmif-respondent-next{border:0;background:var(--hmif-primary);color:#fff}.hmif-respondent-previous{border:1px solid color-mix(in srgb,var(--hmif-text) 22%,transparent);background:var(--hmif-surface);color:var(--hmif-text)}.hmif-respondent-clear{border:0;background:transparent;padding:10px 2px;color:var(--hmif-text);font-weight:600;opacity:.72}.hmif-respondent-progress{border-radius:10px;background:var(--hmif-surface);padding:14px 18px}.hmif-respondent-progress-copy{display:flex;justify-content:space-between;margin-bottom:8px;font-size:12px;opacity:.75}.hmif-respondent-progress-track{height:7px;overflow:hidden;border-radius:99px;background:color-mix(in srgb,var(--hmif-text) 14%,transparent)}.hmif-respondent-progress-fill{display:block;height:100%;background:var(--hmif-primary);transition:width .2s}.hmif-respondent-modebar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px;border:1px solid color-mix(in srgb,var(--hmif-text) 14%,transparent);border-radius:9px;background:var(--hmif-surface);padding:10px 14px;font-size:12px}.hmif-respondent-modebar-main,.hmif-respondent-back{display:flex;align-items:center;gap:10px}.hmif-respondent-back{border:1px solid color-mix(in srgb,var(--hmif-text) 18%,transparent);border-radius:7px;padding:7px 10px;color:var(--hmif-text);font-weight:600;text-decoration:none}.hmif-respondent-back svg{width:16px}.hmif-respondent-brand{margin-top:24px;text-align:center;opacity:.55}@media(max-width:600px){.hmif-respondent-shell{padding:24px 0 48px}.hmif-respondent-canvas{width:calc(100% - 24px)}.hmif-respondent-card{padding:16px}.hmif-respondent-actions,.hmif-respondent-modebar,.hmif-respondent-modebar-main{align-items:flex-start;flex-direction:column}.hmif-respondent-action-group{flex-wrap:wrap}}
</style>

<div class="hmif-respondent-shell" style="--hmif-primary:{{ $theme['primary_color'] }};--hmif-background:{{ $theme['background_color'] }};--hmif-surface:{{ $theme['surface_color'] }};--hmif-text:{{ $themeDark ? '#f4f4f5' : '#111827' }};--hmif-header-font:{{ \App\Support\HmifFormTheme::fontFamily($theme['header_font']) }};--hmif-header-size:{{ $theme['header_size'] }}px;--hmif-question-font:{{ \App\Support\HmifFormTheme::fontFamily($theme['question_font']) }};--hmif-question-size:{{ $theme['question_size'] }}px;--hmif-text-font:{{ \App\Support\HmifFormTheme::fontFamily($theme['text_font']) }};--hmif-text-size:{{ $theme['text_size'] }}px;color-scheme:{{ $themeDark ? 'dark' : 'light' }}">
    <main class="hmif-respondent-canvas">
        @if ($isPreview)
            <div class="hmif-respondent-modebar">
                <div class="hmif-respondent-modebar-main">
                    <a class="hmif-respondent-back" href="{{ \App\Filament\Pages\HmifFormBuilder::getUrl(['form' => $form->uuid]) }}">
                        <x-filament::icon icon="heroicon-m-arrow-left" /> Kembali
                    </a>
                    <span>Preview mode · Jawaban tidak disimpan</span>
                </div>
                <span>{{ ucfirst($form->status) }}</span>
            </div>
        @endif

        @if (! $isPreview && session('submitted'))
            <section class="hmif-respondent-card hmif-respondent-header">
                <h1 class="hmif-respondent-title">Response submitted</h1>
                <p class="hmif-respondent-description">{{ session('submitted_message', 'Respons Anda telah direkam.') }}</p>
            </section>
        @elseif (! $isPreview && $unavailableMessage)
            <section class="hmif-respondent-card hmif-respondent-header">
                <h1 class="hmif-respondent-title">{{ $form->title }}</h1>
                <p>{{ $unavailableMessage }}</p>
            </section>
        @else
            <div class="hmif-respondent-stack">
                @if ($headerImage)
                    <img src="{{ $headerImage }}" class="hmif-respondent-banner" alt="Header {{ $form->title }}">
                @endif

                <form
                    novalidate
                    enctype="multipart/form-data"
                    @unless ($isPreview)
                        method="post"
                        action="{{ route('hmif-forms.public.submit', $form->public_token) }}"
                    @endunless
                    class="hmif-respondent-stack hmif-respondent-form"
                    data-preview="{{ $isPreview ? '1' : '0' }}"
                >
                    @unless ($isPreview)
                        @csrf
                    @endunless

                    @if ($form->show_progress_bar && $sectionCount > 1)
                        <div class="hmif-respondent-progress">
                            <div class="hmif-respondent-progress-copy">
                                <span data-progress-label></span>
                                <span data-progress-percent></span>
                            </div>
                            <div class="hmif-respondent-progress-track"><span class="hmif-respondent-progress-fill" data-progress-fill></span></div>
                        </div>
                    @endif

                    @foreach ($respondentSections as $sectionIndex => $section)
                        @php
                            $targetIndex = $section['after_section_action'] === 'go_to_section'
                                ? $sectionIndexesById->get($section['after_section_target_id'])
                                : null;
                            $sectionSubmits = $section['after_section_action'] === 'submit'
                                || ($section['after_section_action'] === 'continue_next' && $sectionIndex === $sectionCount - 1)
                                || ($section['after_section_action'] === 'go_to_section' && $targetIndex === null);
                            $displayTitle = $sectionIndex === 0 ? $form->title : ($section['title'] ?: $form->title);
                            $displayDescription = $sectionIndex === 0 ? $form->description : ($section['description'] ?: $form->description);
                        @endphp
                        <section class="hmif-respondent-page" data-section-page="{{ $sectionIndex }}" data-navigation-action="{{ $sectionSubmits ? 'submit' : $section['after_section_action'] }}" @if ($targetIndex !== null) data-navigation-target="{{ $targetIndex }}" @endif @if ($sectionIndex > 0) hidden @endif>
                            <div class="hmif-respondent-card hmif-respondent-header">
                                <h1 class="hmif-respondent-title">{{ $displayTitle }}</h1>
                                @if (filled($displayDescription))
                                    <p class="hmif-respondent-description">{{ $displayDescription }}</p>
                                @endif
                                @if ($sectionIndex === 0 && $hasRequired)
                                    <p class="hmif-respondent-required-note">* Wajib diisi</p>
                                @endif
                            </div>

                            @if ($sectionIndex === 0 && ! $isPreview && ($form->collect_email || $form->limit_one_response) && ! auth()->check())
                                <div class="hmif-respondent-card">
                                    <div class="hmif-respondent-question">Email <span class="hmif-respondent-required">*</span></div>
                                    <input class="hmif-respondent-control hmif-respondent-control--single" type="email" name="respondent_email" value="{{ old('respondent_email') }}" required>
                                    @error('respondent_email')
                                        <p class="hmif-respondent-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            @foreach ($section['questions'] as $question)
                                <div class="hmif-respondent-card" role="group">
                                    <div class="hmif-respondent-question">
                                        {{ $question->label }}
                                        @if ($question->is_required)
                                            <span class="hmif-respondent-required">*</span>
                                        @endif
                                    </div>

                                    @if ($question->type === 'file_upload')
                                        @php
                                            $fileSettings = \App\Support\HmifFormFileUpload::normalize($question->settings);
                                            $acceptedMimes = \App\Support\HmifFormFileUpload::allowedMimes($fileSettings);
                                        @endphp
                                        <input class="hmif-respondent-control" style="height:auto;min-height:40px;padding:8px 12px" type="file" name="answers[{{ $question->id }}][]" accept="{{ implode(',', $acceptedMimes) }}" @if ($fileSettings['max_files'] > 1) multiple @endif @required($question->is_required)>
                                        <p class="hmif-respondent-description">Maksimal {{ $fileSettings['max_files'] }} file, {{ $fileSettings['max_file_size_mb'] }} MB per file.</p>
                                    @elseif ($question->type === 'paragraph')
                                        <textarea class="hmif-respondent-control hmif-respondent-control--multiline" name="answers[{{ $question->id }}]" rows="4" placeholder="Long answer text" @required($question->is_required)>{{ $isPreview ? '' : old('answers.' . $question->id) }}</textarea>
                                    @elseif ($question->type === 'radio')
                                        @foreach ($question->options as $option)
                                            <label class="hmif-respondent-option">
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->value ?: $option->label }}" @required($question->is_required)>
                                                <span>{{ $option->label }}</span>
                                            </label>
                                        @endforeach
                                    @elseif ($question->type === 'checkbox')
                                        <div @if ($question->is_required) data-required-checkbox-group @endif>
                                            @foreach ($question->options as $option)
                                                <label class="hmif-respondent-option">
                                                    <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->value ?: $option->label }}">
                                                    <span>{{ $option->label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif ($question->type === 'select')
                                        <select class="hmif-respondent-control hmif-respondent-control--single" name="answers[{{ $question->id }}]" @required($question->is_required)>
                                            <option value="">Pilih opsi</option>
                                            @foreach ($question->options as $option)
                                                <option value="{{ $option->value ?: $option->label }}">{{ $option->label }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input class="hmif-respondent-control hmif-respondent-control--single" type="{{ in_array($question->type, ['email', 'number', 'date', 'time']) ? $question->type : 'text' }}" name="answers[{{ $question->id }}]" value="{{ $isPreview ? '' : old('answers.' . $question->id) }}" placeholder="{{ $question->type === 'email' ? 'Email address' : ($question->type === 'number' ? 'Number' : 'Short answer text') }}" @required($question->is_required)>
                                    @endif

                                    @unless ($isPreview)
                                        @error('answers.' . $question->id)
                                            <p class="hmif-respondent-error">{{ $message }}</p>
                                        @enderror
                                    @endunless
                                </div>
                            @endforeach

                            <div class="hmif-respondent-actions">
                                <div class="hmif-respondent-action-group">
                                    @if ($sectionIndex > 0)
                                        <button type="button" class="hmif-respondent-previous" data-section-back>Back</button>
                                    @endif
                                    @if ($sectionSubmits)
                                        <button type="submit" class="hmif-respondent-submit">{{ $isPreview ? 'Submit Preview' : 'Submit' }}</button>
                                    @else
                                        <button type="button" class="hmif-respondent-next" data-section-next>Berikutnya</button>
                                    @endif
                                </div>
                                <button type="reset" class="hmif-respondent-clear">Clear form</button>
                            </div>
                        </section>
                    @endforeach
                </form>
            </div>
        @endif

        <footer class="hmif-respondent-brand">HMIF Forms</footer>
    </main>
</div>

<script>
document.querySelectorAll('.hmif-respondent-form').forEach(form=>{const pages=[...form.querySelectorAll('[data-section-page]')];if(!pages.length)return;let current=0;let history=[];const label=form.querySelector('[data-progress-label]'),percentText=form.querySelector('[data-progress-percent]'),fill=form.querySelector('[data-progress-fill]');const show=i=>{current=Math.max(0,Math.min(i,pages.length-1));pages.forEach((page,index)=>page.hidden=index!==current);const percent=Math.round((current+1)/pages.length*100);if(label)label.textContent=`Section ${current+1} of ${pages.length}`;if(percentText)percentText.textContent=`${percent}%`;if(fill)fill.style.width=`${percent}%`;window.scrollTo({top:0,behavior:'smooth'})};const validate=page=>{page.querySelectorAll('[data-required-checkbox-group]').forEach(group=>{const boxes=[...group.querySelectorAll('input[type=checkbox]')];if(boxes[0])boxes[0].setCustomValidity(boxes.some(box=>box.checked)?'':'Pertanyaan ini wajib diisi.')});const invalid=[...page.querySelectorAll('input,textarea,select')].find(control=>!control.checkValidity());if(invalid){invalid.reportValidity();return false}return true};form.querySelectorAll('[data-section-next]').forEach(button=>button.addEventListener('click',()=>{if(!validate(pages[current]))return;const page=pages[current];const target=page.dataset.navigationAction==='go_to_section'?Number(page.dataset.navigationTarget):current+1;if(!Number.isInteger(target)||!pages[target])return;history.push(current);show(target)}));form.querySelectorAll('[data-section-back]').forEach(button=>button.addEventListener('click',()=>{if(history.length)show(history.pop())}));form.addEventListener('reset',()=>setTimeout(()=>{history=[];show(0)}));form.addEventListener('submit',event=>{event.preventDefault();if(!validate(pages[current]))return;if(form.dataset.preview==='1'){if(window.FilamentNotification)new FilamentNotification().title('Preview berhasil. Jawaban tidak disimpan.').success().send();return}HTMLFormElement.prototype.submit.call(form)});show(0)});
</script>
