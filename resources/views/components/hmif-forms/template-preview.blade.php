@props(['slug', 'template'])
<div class="hmif-template-canvas">
    @if($slug === 'blank')
        <div class="hmif-template-blank"><div class="hmif-template-blank-icon">+</div><strong>Blank Form</strong></div>
    @else
        <h3>{{ $template['name'] }}</h3>
        <p class="hmif-template-canvas-description">{{ $template['description'] }}</p>
        @foreach(array_slice($template['questions'] ?? [], 0, 3) as $question)
            <div class="hmif-template-question">
                <span class="hmif-template-question-label">{{ $question['label'] }} @if($question['is_required'] ?? false)<span style="color:rgb(220 38 38)">*</span>@endif</span>
                @if(in_array($question['type'], ['radio', 'checkbox'], true))
                    @foreach(array_slice($question['options'] ?? [], 0, 3) as $option)<span class="hmif-template-option"><i class="hmif-template-option-mark {{ $question['type'] === 'radio' ? 'radio' : '' }}"></i>{{ $option }}</span>@endforeach
                @elseif($question['type'] === 'select')
                    <span class="hmif-template-select">Select <b>⌄</b></span>
                @else
                    <div class="hmif-template-answer-line">{{ $question['type'] === 'paragraph' ? 'Long answer' : 'Short answer' }}</div>@if($question['type'] === 'paragraph')<div class="hmif-template-answer-line"></div>@endif
                @endif
            </div>
        @endforeach
    @endif
</div>
