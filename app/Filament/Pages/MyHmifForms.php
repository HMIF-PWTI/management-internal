<?php
namespace App\Filament\Pages;
use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
class MyHmifForms extends Page
{
    use HasHmifFormsNavigation;
    protected static ?string $navigationLabel = 'Form Saya';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'hmif-forms/my-forms';
    protected static ?string $title = 'Form Saya';
    protected string $view = 'filament.pages.hmif-forms.index';
    public function getSubheading(): ?string { return 'Kelola, cari, dan buka kembali formulir Anda.'; }
    protected function getHeaderActions(): array
    {
        return [Action::make('create')->label('Buat Form')->icon(Heroicon::OutlinedPlus)->url(CreateHmifForm::getUrl())];
    }
    public static function getNavigationBadge(): ?string { $user = auth()->user(); return $user ? (string) $user->hmifForms()->count() : null; }
    public function getViewData(): array
    {
        $totalForms = auth()->user()->hmifForms()->count();
        $query = auth()->user()->hmifForms()->withCount('responses');
        if (($search = trim((string) request('search'))) !== '') $query->where('title', 'like', "%{$search}%");
        if (in_array(request('status'), ['draft', 'published', 'closed'], true)) $query->where('status', request('status'));
        match (request('sort')) { 'newest' => $query->latest('created_at'), 'oldest' => $query->oldest('created_at'), 'az' => $query->orderBy('title'), 'za' => $query->orderByDesc('title'), 'responses' => $query->orderByDesc('responses_count'), default => $query->latest('updated_at') };
        return ['forms' => $query->paginate(12)->withQueryString(), 'totalForms' => $totalForms];
    }
}
