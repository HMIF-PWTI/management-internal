<?php

namespace App\Filament\Resources\IfPedias\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
class IfPediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),

                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                FileUpload::make('file_pdf')
                    ->label('File PDF')
                    ->acceptedFileTypes(['application/pdf'])
                    ->disk('public')
                    ->directory('if-pedia')
                    ->visibility('public')
                    ->maxSize(20480) // 20 MB
                    ->required(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    
    }
}
