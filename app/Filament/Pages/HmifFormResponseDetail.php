<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use App\Models\HmifForm;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;

class HmifFormResponseDetail extends Page
{
    use HasHmifFormsNavigation;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'hmif-forms/{form}/responses';
    protected static ?string $title = 'Responses';
    protected Width|string|null $maxContentWidth = Width::Full;
    protected string $view = 'filament.pages.hmif-forms.responses';

    public $form;

    public function mount(string $form): void
    {
        $this->form = HmifForm::where('uuid', $form)->with(['questions.answers.question', 'questions.options', 'questions.responseFiles'])->withCount('responses')->firstOrFail();
        abort_unless($this->form->owner_id === auth()->id(), 403);
    }

    public function getViewData(): array
    {
        return [
            'responses' => $this->form->responses()->with(['answers.question', 'files.question'])->latest('submitted_at')->paginate(20),
            'summary' => $this->form->questions->map(function ($question) {
                $answers = $question->answers->filter(fn ($answer) => filled($answer->selectedValues()));
                $counts = $answers->flatMap->selectedValues()->countBy();

                return [
                    'question' => $question,
                    'answered' => $answers->count(),
                    'options' => $question->options->map(fn ($option) => ['label' => $option->label, 'count' => $counts->get($option->value ?: $option->label, 0)]),
                    'latest' => $answers->sortByDesc('id')->take(5)->map->displayValue(),
                ];
            }),
        ];
    }
}
