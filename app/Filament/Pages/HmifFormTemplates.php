<?php
namespace App\Filament\Pages;
use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use App\Services\HmifFormTemplateService;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
class HmifFormTemplates extends Page
{
    use HasHmifFormsNavigation;
    protected static ?string $navigationLabel = 'Template';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'hmif-forms/templates';
    protected static ?string $title = 'Template';
    protected string $view = 'filament.pages.hmif-forms.templates';
    public function getSubheading(): ?string { return 'Pilih template untuk memulai form baru.'; }
    public function getViewData(): array { return ['templates' => HmifFormTemplateService::TEMPLATES]; }
}
