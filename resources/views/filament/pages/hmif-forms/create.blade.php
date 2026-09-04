<x-filament-panels::page>
    <x-hmif-forms.gallery-styles />
    <p class="text-sm text-gray-600 dark:text-gray-300">Mulai dari form kosong atau pilih blueprint yang paling sesuai.</p>
    <div class="hmif-template-grid hmif-template-gallery">
        @foreach($templates as $slug => $template)
            <x-hmif-forms.template-card :slug="$slug" :template="$template" />
        @endforeach
    </div>
</x-filament-panels::page>
