<?php

namespace App\Filament\Resources\Abouts\Schemas;

use App\Models\Divisi;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AboutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('foto')
                    ->image()
                    ->directory('about'),

                TextInput::make('nama')
                    ->required(),

                TextInput::make('jabatan')
                    ->required(),

                Select::make('id_divisi')
                    ->label('Divisi')
                    ->options(
                        Divisi::query()->pluck('nama', 'id')
                    )
                    ->searchable(),

                TextInput::make('urutan')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_inti')
                    ->default(false),

                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}