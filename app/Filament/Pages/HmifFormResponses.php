<?php
namespace App\Filament\Pages;
use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
class HmifFormResponses extends Page
{
    use HasHmifFormsNavigation;
    protected static ?string $navigationLabel = 'Responses';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'hmif-forms/responses';
    protected static ?string $title = 'Responses';
    protected string $view = 'filament.pages.hmif-forms.response-center';
    public function getViewData(): array { return ['forms' => auth()->user()->hmifForms()->withCount('responses')->latest('updated_at')->paginate(15)]; }
}
