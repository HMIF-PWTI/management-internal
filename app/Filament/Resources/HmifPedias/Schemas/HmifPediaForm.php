<?php

namespace App\Filament\Resources\HmifPedias\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HmifPediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')
                ->required()
                ->maxLength(255),
            Textarea::make('deskripsi')
                ->columnSpanFull(),
            FileUpload::make('file_pdf')
                ->label('File PDF')
                ->acceptedFileTypes(['application/pdf'])
                ->disk('public')
                ->directory('hmif-pedia')
                ->visibility('public')
                ->maxSize(102400) // 100 MB
                ->required(),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
