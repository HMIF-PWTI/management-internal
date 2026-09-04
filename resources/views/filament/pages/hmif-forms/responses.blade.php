<x-filament-panels::page>
    <div class="flex flex-wrap justify-between gap-4"><div><h2 class="text-xl font-bold text-gray-950 dark:text-white">{{ $form->title }}</h2><p class="text-gray-500"><strong>{{ number_format($form->responses_count) }}</strong> responses</p></div><x-filament::button color="gray" :href="\App\Filament\Pages\HmifFormBuilder::getUrl(['form'=>$form->uuid])">Questions</x-filament::button></div>
    <x-filament::tabs><x-filament::tabs.item active>Summary</x-filament::tabs.item><x-filament::tabs.item tag="a" href="#question">Question</x-filament::tabs.item><x-filament::tabs.item tag="a" href="#individual">Individual</x-filament::tabs.item></x-filament::tabs>
    <div id="question" class="grid gap-4 md:grid-cols-2">
        @foreach($form->questions as $question)
            <x-filament::section :heading="$question->label">
                <p class="mb-3 text-sm text-gray-500">{{ $question->type==='file_upload' ? $question->responseFiles->count().' files' : $question->answers->count().' answers' }}</p>
                @if($question->type==='file_upload')
                    @foreach($question->responseFiles->take(5) as $file)<div class="flex items-center justify-between gap-3 border-b py-2 text-sm dark:border-white/10"><span>{{ $file->original_name }} · {{ number_format($file->size/1048576,2) }} MB</span><a class="text-primary-600" href="{{ route('hmif-forms.actions.response-files.download',$file) }}">Download</a></div>@endforeach
                @elseif(in_array($question->type,['radio','checkbox','select']))
                    @foreach($question->answers->groupBy('value')->sortByDesc(fn($answers)=>$answers->count()) as $value=>$answers)<div class="flex justify-between border-b py-2 text-sm dark:border-white/10"><span>{{ $value }}</span><strong>{{ $answers->count() }}</strong></div>@endforeach
                @else
                    @foreach($question->answers->take(5) as $answer)<p class="border-b py-2 text-sm dark:border-white/10">{{ $answer->value?:'—' }}</p>@endforeach
                @endif
            </x-filament::section>
        @endforeach
    </div>
    <div id="individual" class="space-y-4"><h2 class="text-lg font-semibold dark:text-white">Individual</h2>
        @forelse($responses as $response)
            <x-filament::section :heading="'Response #'.$response->id"><p class="mb-4 text-sm text-gray-500">{{ $response->submitted_at->format('d M Y H:i') }}</p>
                @foreach($response->answers as $answer)<div class="mb-3"><strong class="text-sm dark:text-white">{{ $answer->question->label }}</strong>
                    @if($answer->question->type==='file_upload')
                        @forelse($response->files->where('question_id',$answer->question_id) as $file)<p class="text-sm"><a class="text-primary-600" href="{{ route('hmif-forms.actions.response-files.download',$file) }}">{{ $file->original_name }}</a> · {{ number_format($file->size/1048576,2) }} MB</p>@empty<p class="text-sm text-gray-500">—</p>@endforelse
                    @else<p class="text-sm text-gray-600 dark:text-gray-300">{{ $answer->value?:'—' }}</p>@endif
                </div>@endforeach
            </x-filament::section>
        @empty<p class="text-gray-500">Belum ada response.</p>@endforelse
    </div>
    {{ $responses->links() }}
</x-filament-panels::page>
