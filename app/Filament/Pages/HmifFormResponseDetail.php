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
        $this->form = HmifForm::where('uuid', $form)->with(['questions.answers', 'questions.responseFiles'])->withCount('responses')->firstOrFail();
        abort_unless($this->form->owner_id === auth()->id(), 403);
    }

    public function getViewData(): array
    {
        return ['responses' => $this->form->responses()->with(['answers.question', 'files.question'])->latest('submitted_at')->paginate(20)];
    }
}
