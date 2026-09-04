<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use App\Models\HmifForm;
use Filament\Pages\Page;

class HmifFormPreview extends Page
{
    use HasHmifFormsNavigation;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'hmif-forms/{form}/preview';
    protected static ?string $title = 'Preview Form';
    protected string $view = 'filament.pages.hmif-forms.preview';

    public $form;

    public function mount(string $form): void
    {
        $this->form = HmifForm::where('uuid', $form)->with(['sections.questions.options', 'questions.options'])->firstOrFail();
        abort_unless($this->form->owner_id === auth()->id(), 403);
    }
}
