<x-filament-panels::page>
    <style>
        .hmif-option-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:.25rem .75rem;padding:.5rem 0;font-size:.875rem;color:rgb(55 65 81)}
        .hmif-answer-row{border-bottom:1px solid rgb(243 244 246);padding:.5rem 0;font-size:.875rem;color:rgb(55 65 81)}.dark .hmif-answer-row{border-color:rgb(255 255 255/.1);color:rgb(209 213 219)}
        .hmif-option-track{grid-column:1/-1;height:8px;overflow:hidden;border-radius:4px;background:rgb(243 244 246)}
        .hmif-option-fill{display:block;height:100%;border-radius:4px;background:var(--primary-500)}
        .hmif-option-count{font-variant-numeric:tabular-nums;color:rgb(17 24 39);font-weight:600}
        .dark .hmif-option-row{color:rgb(209 213 219)}.dark .hmif-option-track{background:rgb(255 255 255/.08)}.dark .hmif-option-count{color:#fff}
    </style>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-950 dark:text-white">{{ $form->title }}</h2>
            <p class="text-gray-500 dark:text-gray-400"><strong>{{ number_format($form->responses_count) }}</strong> responses</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <x-filament::button tag="a" color="gray" icon="heroicon-m-pencil-square" :href="\App\Filament\Pages\HmifFormBuilder::getUrl(['form' => $form->uuid])">Edit Form</x-filament::button>
            @if ($form->responses_count > 0)
                <x-filament::button tag="a" icon="heroicon-m-arrow-down-tray" :href="route('hmif-forms.actions.responses.export', $form)">Export CSV</x-filament::button>
            @endif
        </div>
    </div>

    <div style="display:grid;gap:1.5rem;min-width:0" x-data="{ tab: @js(request()->has('page') ? 'individual' : 'summary') }">
        <x-filament::tabs>
            <x-filament::tabs.item alpine-active="tab === 'summary'" x-on:click="tab = 'summary'">Summary</x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="tab === 'individual'" x-on:click="tab = 'individual'">Individual</x-filament::tabs.item>
        </x-filament::tabs>

        <div x-show="tab === 'summary'" class="grid gap-4 md:grid-cols-2">
            @forelse ($summary as $item)
                @php $question = $item['question']; @endphp
                <x-filament::section :heading="$question->label">
                    <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">{{ $question->type === 'file_upload' ? $question->responseFiles->count() . ' files' : $item['answered'] . ' answers' }}</p>
                    @if ($question->type === 'file_upload')
                        @foreach ($question->responseFiles->take(5) as $file)
                            <div class="hmif-answer-row flex items-center justify-between gap-3">
                                <span class="min-w-0 truncate">{{ $file->original_name }} · {{ number_format($file->size / 1048576, 2) }} MB</span>
                                <a class="text-primary-600 dark:text-primary-400" href="{{ route('hmif-forms.actions.response-files.download', $file) }}">Download</a>
                            </div>
                        @endforeach
                    @elseif (in_array($question->type, ['radio', 'checkbox', 'select']))
                        @foreach ($item['options'] as $option)
                            @php $percent = $item['answered'] ? round($option['count'] / $item['answered'] * 100) : 0; @endphp
                            <div class="hmif-option-row">
                                <span class="min-w-0 truncate">{{ $option['label'] }}</span>
                                <span class="hmif-option-count">{{ $option['count'] }} ({{ $percent }}%)</span>
                                <span class="hmif-option-track"><span class="hmif-option-fill" style="width:{{ $percent }}%"></span></span>
                            </div>
                        @endforeach
                    @else
                        @forelse ($item['latest'] as $value)
                            <p class="hmif-answer-row">{{ $value }}</p>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada jawaban.</p>
                        @endforelse
                    @endif
                </x-filament::section>
            @empty
                <p class="text-gray-500">Form ini belum punya pertanyaan.</p>
            @endforelse
        </div>

        <div x-show="tab === 'individual'" x-cloak class="space-y-4">
            @forelse ($responses as $response)
                <x-filament::section :heading="'Response #' . $response->id">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ $response->submitted_at->format('d M Y H:i') }}@if ($response->respondent_email) · {{ $response->respondent_email }}@endif</p>
                    @foreach ($response->answers->sortBy('question.sort_order') as $answer)
                        <div class="mb-3">
                            <strong class="text-sm dark:text-white">{{ $answer->question->label }}</strong>
                            @if ($answer->question->type === 'file_upload')
                                @forelse ($response->files->where('question_id', $answer->question_id) as $file)
                                    <p class="text-sm dark:text-gray-300"><a class="text-primary-600 dark:text-primary-400" href="{{ route('hmif-forms.actions.response-files.download', $file) }}">{{ $file->original_name }}</a> · {{ number_format($file->size / 1048576, 2) }} MB</p>
                                @empty
                                    <p class="text-sm text-gray-500">—</p>
                                @endforelse
                            @else
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $answer->displayValue() ?: '—' }}</p>
                            @endif
                        </div>
                    @endforeach
                </x-filament::section>
            @empty
                <p class="text-gray-500">Belum ada response.</p>
            @endforelse
            {{ $responses->links() }}
        </div>
    </div>
</x-filament-panels::page>
