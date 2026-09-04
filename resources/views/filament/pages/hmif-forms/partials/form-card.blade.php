@php
    $builderUrl = \App\Filament\Pages\HmifFormBuilder::getUrl(['form' => $form->uuid]);
    $previewUrl = \App\Filament\Pages\HmifFormPreview::getUrl(['form' => $form->uuid]);
    $canShare = $form->status === 'published' && filled($form->public_token);
    $publicUrl = $canShare ? route('hmif-forms.public.show', $form->public_token) : null;
@endphp
<article class="hmif-recent-form-card rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <x-filament::badge :color="match($form->status) { 'published' => 'success', 'closed' => 'danger', default => 'gray' }">{{ ucfirst($form->status) }}</x-filament::badge>
    <h3 class="mt-4 text-base font-semibold text-gray-950 dark:text-white">{{ filled($form->title) ? $form->title : 'Untitled form' }}</h3>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300"><strong>{{ number_format($form->responses_count) }}</strong> responses</p>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Updated {{ $form->updated_at->diffForHumans() }}</p>

    <div class="hmif-recent-form-actions mt-5" x-on:click.stop>
        <x-filament::button tag="a" size="sm" :href="$builderUrl" icon="heroicon-m-pencil-square">Open</x-filament::button>
        <x-filament::button tag="a" size="sm" color="gray" :href="$previewUrl" target="_blank" rel="noopener" icon="heroicon-m-eye">Preview</x-filament::button>

        <x-filament::modal :id="'share-recent-form-'.$form->id" width="lg" alignment="center" icon="heroicon-o-share" heading="Bagikan Form" :close-by-escaping="true" :close-by-clicking-away="true">
            <x-slot name="trigger"><x-filament::button size="sm" color="gray" type="button" icon="heroicon-m-share">Share</x-filament::button></x-slot>
            @if($canShare)
                <div class="space-y-4" x-data="{async copy(){let copied=false;try{if(navigator.clipboard&&window.isSecureContext){await navigator.clipboard.writeText(@js($publicUrl));copied=true}}catch(error){}if(!copied){const input=$el.querySelector('input');input.focus();input.select();copied=document.execCommand('copy')}new FilamentNotification().title(copied?'Link berhasil disalin.':'Gagal menyalin link.')[copied?'success':'danger']().send()}}">
                    <label class="block text-sm font-medium text-gray-950 dark:text-white">Public Link</label>
                    <x-filament::input.wrapper><x-filament::input :value="$publicUrl" readonly x-on:focus="$el.select()"/></x-filament::input.wrapper>
                    <div class="flex flex-wrap gap-2"><x-filament::button type="button" x-on:click="copy()">Copy Link</x-filament::button><x-filament::button tag="a" type="button" color="gray" :href="$publicUrl" target="_blank" rel="noopener">Open Link</x-filament::button></div>
                </div>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-300">Form belum dipublikasikan.</p>
            @endif
        </x-filament::modal>

        <form method="post" action="{{ route('hmif-forms.actions.duplicate', $form) }}" x-on:submit="duplicating=true" x-data="{duplicating:false}">
            @csrf
            <input type="hidden" name="return_to" value="dashboard">
            <x-filament::button size="sm" color="gray" icon="heroicon-m-document-duplicate" type="submit" x-bind:disabled="duplicating"><span x-show="!duplicating">Duplicate</span><span x-show="duplicating" x-cloak>Duplicating...</span></x-filament::button>
        </form>

        <x-filament::modal :id="'delete-form-compact-'.$form->id" width="md" alignment="center" icon="heroicon-o-trash" icon-color="danger" heading="Hapus Form?" :close-by-escaping="true" :close-by-clicking-away="true">
            <x-slot name="trigger"><x-filament::button size="sm" color="danger" type="button" icon="heroicon-m-trash">Delete</x-filament::button></x-slot>
            <p class="text-sm text-gray-600 dark:text-gray-300">Form <strong class="text-gray-950 dark:text-white">“{{ filled($form->title) ? $form->title : 'Untitled form' }}”</strong> beserta seluruh response dan jawaban terkait akan dihapus. Tindakan ini tidak dapat dibatalkan.</p>
            <x-slot name="footer"><div class="flex justify-end gap-3" x-data="{deleting:false}"><x-filament::button color="gray" type="button" x-on:click="$dispatch('close-modal',{id:'delete-form-compact-{{ $form->id }}'})" x-bind:disabled="deleting">Batal</x-filament::button><form method="post" action="{{ route('hmif-forms.actions.destroy',$form) }}" x-on:submit="deleting=true">@csrf @method('delete')<x-filament::button color="danger" icon="heroicon-m-trash" type="submit" x-bind:disabled="deleting"><span x-show="!deleting">Hapus Form</span><span x-show="deleting" x-cloak>Menghapus...</span></x-filament::button></form></div></x-slot>
        </x-filament::modal>
    </div>
</article>
