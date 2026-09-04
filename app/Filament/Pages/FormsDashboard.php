<?php
namespace App\Filament\Pages;
use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
class FormsDashboard extends Page
{
    use HasHmifFormsNavigation;
    protected static ?string $navigationLabel = 'Dashboard Forms';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'hmif-forms';
    protected static ?string $title = 'HMIF Forms';
    protected string $view = 'filament.pages.hmif-forms.dashboard';
    public function getViewData(): array
    {
        $query = auth()->user()->hmifForms();
        return ['stats' => ['total' => (clone $query)->count(), 'published' => (clone $query)->where('status', 'published')->count(), 'draft' => (clone $query)->where('status', 'draft')->count(), 'responses' => (clone $query)->withCount('responses')->get()->sum('responses_count')], 'forms' => (clone $query)->withCount('responses')->latest('updated_at')->limit(6)->get()];
    }
}
