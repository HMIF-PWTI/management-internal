<?php
namespace App\Filament\Pages;
use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use App\Services\HmifFormTemplateService;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
class CreateHmifForm extends Page
{
    use HasHmifFormsNavigation;
    protected static ?string $navigationLabel = 'Buat Form';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentPlus;
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'hmif-forms/create';
    protected static ?string $title = 'Buat Form';
    protected string $view = 'filament.pages.hmif-forms.create';
    public function getViewData(): array { return ['templates' => HmifFormTemplateService::TEMPLATES]; }
}
