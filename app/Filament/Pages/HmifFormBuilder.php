<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use App\Models\HmifForm;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;

class HmifFormBuilder extends Page
{
    use HasHmifFormsNavigation;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'hmif-forms/{form}/edit';
    protected static ?string $title = 'Form Builder';
    protected Width|string|null $maxContentWidth = Width::Full;
    protected string $view = 'filament.pages.hmif-forms.builder';

    public $form;

    public function mount(string $form): void
    {
        $this->form = HmifForm::where('uuid', $form)->with(['sections', 'questions.options'])->withCount('responses')->firstOrFail();
        abort_unless($this->form->owner_id === auth()->id(), 403);
    }

    public function getTitle(): string
    {
        return $this->form->title;
    }

    public function getPageClasses(): array
    {
        return ['hmif-form-builder-shell'];
    }
}
