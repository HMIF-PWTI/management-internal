<x-filament-panels::page>
    <style>
        .hmif-dash{--dash-surface:#fff;--dash-border:rgb(229 231 235);--dash-ink:rgb(17 24 39);--dash-muted:rgb(107 114 128);--dash-track:rgb(243 244 246);display:grid;gap:1.25rem;min-width:0}
        .dark .hmif-dash{--dash-surface:rgb(24 24 27);--dash-border:rgb(255 255 255/.1);--dash-ink:#fff;--dash-muted:rgb(161 161 170);--dash-track:rgb(255 255 255/.08)}
        .hmif-dash-card{min-width:0;border:1px solid var(--dash-border);border-radius:.875rem;background:var(--dash-surface);padding:1.25rem}
        .hmif-dash-title{margin:0;color:var(--dash-ink);font-size:.95rem;font-weight:650}
        .hmif-dash-note{margin:.15rem 0 0;color:var(--dash-muted);font-size:.8125rem}
        .hmif-dash-hero{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;padding:1.5rem;background:linear-gradient(120deg,color-mix(in srgb,var(--primary-500) 12%,var(--dash-surface)),var(--dash-surface) 62%)}
        .hmif-dash-hero h2{margin:0;color:var(--dash-ink);font-size:1.5rem;font-weight:700;line-height:1.25}
        .hmif-dash-hero p{margin:.25rem 0 0;color:var(--dash-muted);font-size:.9rem}
        .hmif-dash-actions{display:flex;flex-wrap:wrap;gap:.5rem}
        .hmif-dash-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem}
        .hmif-dash-stat{display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem}
        .hmif-dash-stat-label{margin:0;color:var(--dash-muted);font-size:.8125rem;font-weight:500}
        .hmif-dash-stat-value{margin:.35rem 0 0;color:var(--dash-ink);font-size:1.875rem;font-weight:700;line-height:1;font-variant-numeric:tabular-nums}
        .hmif-dash-stat-icon{display:grid;height:2.25rem;width:2.25rem;flex:none;place-items:center;border-radius:.625rem;background:var(--dash-track);color:var(--dash-muted)}
        .hmif-dash-split{display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1fr);gap:1rem}
        .hmif-dash-chart{display:grid;grid-template-columns:repeat(14,minmax(0,1fr));gap:4px;height:180px;margin-top:1.25rem;border-bottom:1px solid var(--dash-border)}
        .hmif-dash-bar-cell{position:relative;display:flex;align-items:flex-end;justify-content:center;outline:none}
        .hmif-dash-bar{display:block;width:min(100%,28px);min-height:2px;border-radius:4px 4px 0 0;background:var(--primary-500);transition:background-color .12s}
        .hmif-dash-bar.is-empty{border-radius:0;background:var(--dash-border)}
        .hmif-dash-bar-cell:hover .hmif-dash-bar,.hmif-dash-bar-cell:focus-visible .hmif-dash-bar{background:var(--primary-600)}
        .hmif-dash-tip{position:absolute;bottom:calc(100% - 1.75rem);left:50%;z-index:10;display:none;transform:translateX(-50%);border-radius:.375rem;background:rgb(24 24 27);padding:.25rem .5rem;color:#fff;font-size:.75rem;white-space:nowrap;pointer-events:none}
        .hmif-dash-bar-cell:hover .hmif-dash-tip,.hmif-dash-bar-cell:focus-visible .hmif-dash-tip{display:block}
        .hmif-dash-bar-cell:first-child .hmif-dash-tip{left:0;transform:none}.hmif-dash-bar-cell:last-child .hmif-dash-tip{right:0;left:auto;transform:none}
        .hmif-dash-axis{display:flex;justify-content:space-between;margin-top:.5rem;color:var(--dash-muted);font-size:.75rem}
        .hmif-dash-sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap}
        .hmif-dash-top{margin:1rem 0 0;padding:0;list-style:none}
        .hmif-dash-top li{border-top:1px solid var(--dash-border)}
        .hmif-dash-top a{display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.625rem 0;color:var(--dash-ink);font-size:.875rem;text-decoration:none}
        .hmif-dash-top a:hover{color:var(--primary-600)}
        .hmif-dash-top span:first-child{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .hmif-dash-top strong{font-variant-numeric:tabular-nums}
        .hmif-dash-templates{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:.75rem;margin-top:1rem}
        .hmif-dash-template{display:block;width:100%;height:100%;border:1px solid var(--dash-border);border-radius:.625rem;background:transparent;padding:.75rem;color:var(--dash-ink);text-align:left;cursor:pointer;transition:border-color .15s}
        .hmif-dash-template:hover,.hmif-dash-template:focus-visible{border-color:var(--primary-500)}
        .hmif-dash-template strong{display:block;font-size:.875rem;font-weight:600}
        .hmif-dash-template span{display:block;margin-top:.15rem;color:var(--dash-muted);font-size:.75rem;line-height:1.35}
        .hmif-dash-forms{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;margin-top:1rem}
        .hmif-recent-form-card{position:relative;min-width:0}
        .hmif-recent-form-actions{position:relative;z-index:10;display:flex;flex-wrap:wrap;gap:.5rem;pointer-events:auto}
        .hmif-recent-form-actions :is(a,button,form){pointer-events:auto}
        .hmif-dash-head{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.5rem}
        .hmif-dash-head a{color:var(--primary-600);font-size:.8125rem;font-weight:600;text-decoration:none}
        .dark .hmif-dash-head a,.dark .hmif-dash-top a:hover{color:var(--primary-400)}
        @media(max-width:1279px){.hmif-dash-forms{grid-template-columns:repeat(2,minmax(0,1fr))}.hmif-dash-templates{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:1023px){.hmif-dash-split{grid-template-columns:minmax(0,1fr)}.hmif-dash-stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:639px){.hmif-dash-forms,.hmif-dash-templates{grid-template-columns:minmax(0,1fr)}.hmif-dash-chart{gap:2px}}
    </style>

    @php
        $createUrl = \App\Filament\Pages\CreateHmifForm::getUrl();
        $statCards = [
            ['label' => 'Total Form', 'value' => $stats['total'], 'icon' => 'heroicon-o-rectangle-stack'],
            ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'heroicon-o-globe-alt'],
            ['label' => 'Draft', 'value' => $stats['draft'], 'icon' => 'heroicon-o-pencil-square'],
            ['label' => 'Total Response', 'value' => $stats['responses'], 'icon' => 'heroicon-o-inbox-arrow-down'],
        ];
    @endphp

    <div class="hmif-dash">
        <section class="hmif-dash-card hmif-dash-hero">
            <div>
                <h2>Halo, {{ auth()->user()->getFilamentName() }}</h2>
                <p>{{ $recentTotal > 0 ? number_format($recentTotal) . ' response masuk dalam 14 hari terakhir.' : 'Buat, bagikan, dan kelola formulir Anda.' }}</p>
            </div>
            <div class="hmif-dash-actions">
                <x-filament::button tag="a" icon="heroicon-m-plus" :href="$createUrl">Buat Form</x-filament::button>
                <x-filament::button tag="a" color="gray" icon="heroicon-m-chart-bar" :href="\App\Filament\Pages\HmifFormResponses::getUrl()">Lihat Responses</x-filament::button>
            </div>
        </section>

        <section class="hmif-dash-stats" aria-label="Ringkasan form">
            @foreach ($statCards as $card)
                <div class="hmif-dash-card hmif-dash-stat">
                    <div>
                        <p class="hmif-dash-stat-label">{{ $card['label'] }}</p>
                        <p class="hmif-dash-stat-value">{{ number_format($card['value']) }}</p>
                    </div>
                    <span class="hmif-dash-stat-icon"><x-filament::icon :icon="$card['icon']" class="h-5 w-5" /></span>
                </div>
            @endforeach
        </section>

        <div class="hmif-dash-split">
            <section class="hmif-dash-card">
                <h3 class="hmif-dash-title">Response per hari</h3>
                <p class="hmif-dash-note">14 hari terakhir{{ $recentTotal > 0 ? ', puncak ' . number_format($dailyMax) . ' response per hari' : '' }}</p>
                <div class="hmif-dash-chart" aria-hidden="true">
                    @foreach ($daily as $day)
                        <div class="hmif-dash-bar-cell" tabindex="0">
                            <span class="hmif-dash-bar {{ $day['count'] === 0 ? 'is-empty' : '' }}" style="height:{{ $day['count'] / $dailyMax * 100 }}%"></span>
                            <span class="hmif-dash-tip">{{ $day['date']->translatedFormat('d M') }}: {{ number_format($day['count']) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="hmif-dash-axis" aria-hidden="true">
                    <span>{{ $daily->first()['date']->translatedFormat('d M') }}</span>
                    <span>{{ $daily->last()['date']->translatedFormat('d M') }}</span>
                </div>
                <table class="hmif-dash-sr">
                    <caption>Jumlah response per hari</caption>
                    <thead><tr><th>Tanggal</th><th>Response</th></tr></thead>
                    <tbody>
                        @foreach ($daily as $day)
                            <tr><td>{{ $day['date']->translatedFormat('d M Y') }}</td><td>{{ $day['count'] }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <section class="hmif-dash-card">
                <h3 class="hmif-dash-title">Form teraktif</h3>
                <p class="hmif-dash-note">Berdasarkan jumlah response</p>
                @if ($topForms->isNotEmpty())
                    <ol class="hmif-dash-top">
                        @foreach ($topForms as $form)
                            <li><a href="{{ \App\Filament\Pages\HmifFormResponseDetail::getUrl(['form' => $form->uuid]) }}"><span>{{ filled($form->title) ? $form->title : 'Untitled form' }}</span><strong>{{ number_format($form->responses_count) }}</strong></a></li>
                        @endforeach
                    </ol>
                @else
                    <p class="hmif-dash-note" style="margin-top:1rem">Belum ada response. Bagikan form yang sudah dipublikasikan untuk mulai mengumpulkan jawaban.</p>
                @endif
            </section>
        </div>

        <section class="hmif-dash-card">
            <div class="hmif-dash-head">
                <h3 class="hmif-dash-title">Mulai dari template</h3>
                <a href="{{ $createUrl }}">Form kosong</a>
            </div>
            <div class="hmif-dash-templates">
                @foreach ($templates as $slug => $template)
                    <form method="post" action="{{ route('hmif-forms.actions.store') }}">
                        @csrf
                        <input type="hidden" name="template" value="{{ $slug }}">
                        <button type="submit" class="hmif-dash-template"><strong>{{ $template['name'] }}</strong><span>{{ $template['description'] }}</span></button>
                    </form>
                @endforeach
            </div>
        </section>

        <section>
            <div class="hmif-dash-head">
                <h3 class="hmif-dash-title">Form terbaru</h3>
                @if ($stats['total'] > $forms->count())
                    <a href="{{ \App\Filament\Pages\MyHmifForms::getUrl() }}">Lihat semua</a>
                @endif
            </div>
            @if ($forms->isNotEmpty())
                <div class="hmif-dash-forms">
                    @foreach ($forms as $form)
                        @include('filament.pages.hmif-forms.partials.form-card', compact('form'))
                    @endforeach
                </div>
            @else
                <x-filament::empty-state icon="heroicon-o-document-plus" heading="Belum ada form" description="Buat form pertama Anda untuk mulai mengumpulkan response.">
                    <x-slot name="footer"><x-filament::button tag="a" icon="heroicon-m-plus" :href="$createUrl">Buat Form</x-filament::button></x-slot>
                </x-filament::empty-state>
            @endif
        </section>
    </div>
</x-filament-panels::page>
