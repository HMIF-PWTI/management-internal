<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
                    ->directory('users')
                    ->nullable(),

                TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('nim')
                    ->label('NIM')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->nullable(),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull()
                    ->nullable(),

                Select::make('id_divisi')
                    ->label('Divisi')
                    ->relationship('divisi', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('status_keanggotaan')
                    ->label('Status Keanggotaan')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('nonaktif')
                    ->required(),
            ]);
    }
}