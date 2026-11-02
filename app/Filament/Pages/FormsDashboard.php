<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasHmifFormsNavigation;
use App\Models\HmifFormResponse;
use App\Services\HmifFormTemplateService;
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
        $forms = (clone $query)->withCount('responses')->latest('updated_at')->get();
        $days = 14;

        $submissions = HmifFormResponse::whereIn('form_id', $forms->pluck('id'))
            ->where('submitted_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->get(['submitted_at'])
            ->countBy(fn ($response) => $response->submitted_at->format('Y-m-d'));

        $daily = collect(range($days - 1, 0))->map(fn (int $daysAgo) => [
            'date' => $date = now()->subDays($daysAgo),
            'count' => $submissions->get($date->format('Y-m-d'), 0),
        ]);

        return [
            'stats' => [
                'total' => $forms->count(),
                'published' => $forms->where('status', 'published')->count(),
                'draft' => $forms->where('status', 'draft')->count(),
                'responses' => $forms->sum('responses_count'),
            ],
            'daily' => $daily,
            'dailyMax' => max(1, $daily->max('count')),
            'recentTotal' => $daily->sum('count'),
            'forms' => $forms->take(6),
            'topForms' => $forms->where('responses_count', '>', 0)->sortByDesc('responses_count')->take(5),
            'templates' => collect(HmifFormTemplateService::TEMPLATES)->except('blank'),
        ];
    }
}
