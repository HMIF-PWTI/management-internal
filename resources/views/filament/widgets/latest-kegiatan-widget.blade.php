@php
    $kegiatans = \App\Models\Kegiatan::latest()->take(5)->get();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-xl font-bold mb-4">
            Kegiatan Terbaru
        </h2>

        <div class="space-y-4">
            @foreach ($kegiatans as $kegiatan)
                <div class="border rounded-xl p-4">
                    <h3 class="font-bold">
                        {{ $kegiatan->nama }}
                    </h3>

                    <p class="text-sm">
                        {{ $kegiatan->tanggal }}
                    </p>

                    <p class="text-sm mt-2">
                        {{ $kegiatan->deskripsi }}
                    </p>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>