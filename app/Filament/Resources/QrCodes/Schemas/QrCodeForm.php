<?php

namespace App\Filament\Resources\QrCodes\Schemas;

use App\Models\QrCode;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class QrCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),

                Section::make('QR Code')
                    ->columns(2)
                    ->schema([
                        Image::make(
                            fn (?QrCode $record): string => $record?->qr_image_url ?? '',
                            'QR Code preview',
                        )
                            ->imageSize(180)
                            ->visible(fn (?QrCode $record): bool => filled($record?->qr_image_url))
                            ->columnSpanFull(),

                        TextInput::make('link')
                            ->label('Link')
                            ->url()
                            ->required()
                            ->maxLength(2048)
                            ->columnSpanFull(),

                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->disk('public')
                            ->directory('qr-logos')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'])
                            ->maxSize(2048)
                            ->image()
                            ->downloadable()
                            ->openable()
                            ->deletable(),

                        TextInput::make('qr_color')
                            ->label('QR Code Color')
                            ->required()
                            ->regex('/^#[0-9A-Fa-f]{6}$/')
                            ->default('#0f0606'),

                        TextInput::make('background_color')
                            ->label('Background Color')
                            ->required()
                            ->regex('/^#[0-9A-Fa-f]{6}$/')
                            ->default('#ffffff'),

                        Select::make('frame')
                            ->options([
                                'none' => 'No frame',
                                'simple' => 'Simple frame',
                                'scan' => 'Scan me frame',
                            ])
                            ->required()
                            ->default('scan'),

                        Select::make('shape')
                            ->options([
                                'square' => 'Square',
                                'dots' => 'Dots',
                                'rounded' => 'Rounded',
                                'extra-rounded' => 'Extra Rounded',
                                'classy' => 'Classy',
                                'classy-rounded' => 'Classy Rounded',
                            ])
                            ->required()
                            ->default('square'),

                        \Filament\Forms\Components\Toggle::make('scan_tracking')
                            ->label('Scan Tracking')
                            ->default(true),

                        Hidden::make('qr_image_url'),
                    ]),
            ]);
    }
}
