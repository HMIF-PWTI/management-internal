@props(['slug', 'template'])
<article class="hmif-template-card">
    <div class="hmif-template-canvas-wrap" aria-label="Preview {{ $template['name'] }}">
        <x-hmif-forms.template-preview :slug="$slug" :template="$template" />
    </div>
    <div class="hmif-template-footer">
        <h3 class="hmif-template-name">{{ $template['name'] }}</h3>
        <p class="hmif-template-description">{{ $template['description'] ?: 'Mulai dari form kosong tanpa pertanyaan.' }}</p>
        <form class="hmif-template-action" method="post" action="{{ route('hmif-forms.actions.store') }}">
            @csrf
            <input type="hidden" name="template" value="{{ $slug }}">
            <x-filament::button type="submit" icon="heroicon-m-plus">Gunakan</x-filament::button>
        </form>
    </div>
</article>
