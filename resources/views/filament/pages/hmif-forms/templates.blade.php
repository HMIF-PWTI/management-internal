<x-filament-panels::page>
    <x-hmif-forms.gallery-styles />
    <div class="hmif-template-grid hmif-template-gallery">
        @foreach($templates as $slug => $template)
            <x-hmif-forms.template-card :slug="$slug" :template="$template" />
        @endforeach
    </div>
</x-filament-panels::page>
