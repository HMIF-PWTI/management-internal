@php
    $user = auth()->user();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-2">
            <h2 class="text-2xl font-bold">
                Halo, {{ $user?->nama ?? $user?->email ?? 'User' }}
            </h2>

            <p>
                Divisi:
                {{ $user?->divisi?->nama ?? '-' }}
            </p>

            <p>
                Status:
                {{ $user?->status_keanggotaan ?? '-' }}
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>