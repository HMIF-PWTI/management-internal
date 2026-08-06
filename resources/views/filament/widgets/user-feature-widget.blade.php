@php
    $user = auth()->user();

    $fiturs = $user?->divisi?->fiturs ?? collect();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-xl font-bold mb-4">
            Fitur Saya
        </h2>

        <div class="flex flex-wrap gap-2">
            @foreach ($fiturs as $fitur)
                <div class="px-4 py-2 rounded-xl bg-primary-500 text-white">
                    {{ $fitur->nama }}
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>