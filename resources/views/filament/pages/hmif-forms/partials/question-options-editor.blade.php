<div x-show="['radio', 'checkbox', 'select'].includes(type)" x-cloak class="mt-5 space-y-3">
    <label class="block text-sm font-medium text-gray-950 dark:text-white">Opsi</label>
    <template x-for="(option, index) in options" :key="optionKey + '-' + index">
        <div class="flex items-center gap-3">
            <span class="flex w-6 shrink-0 justify-center text-sm text-gray-500" x-text="type === 'select' ? (index + 1) + '.' : (type === 'checkbox' ? '□' : '○')"></span>
            <x-filament::input.wrapper class="min-w-0 flex-1">
                <x-filament::input type="text" name="options[]" x-model="options[index]" x-bind:placeholder="'Option ' + (index + 1)" maxlength="255" />
            </x-filament::input.wrapper>
            <x-filament::icon-button type="button" color="danger" icon="heroicon-m-trash" label="Hapus opsi" x-on:click="removeOption(index)" x-bind:disabled="options.length <= 1" />
        </div>
    </template>
    @error('options')<p class="text-sm text-danger-600">{{ $message }}</p>@enderror
    @error('options.*')<p class="text-sm text-danger-600">{{ $message }}</p>@enderror
    <div class="flex flex-wrap items-center gap-3 pt-1">
        <x-filament::button type="button" size="sm" color="gray" icon="heroicon-m-plus" x-on:click="addOption()">Tambah opsi</x-filament::button>
        <button type="button" x-show="['radio', 'checkbox'].includes(type)" x-on:click="addOther()" class="text-sm font-medium text-primary-600 hover:text-primary-500">Tambah "Lainnya"</button>
    </div>
</div>
