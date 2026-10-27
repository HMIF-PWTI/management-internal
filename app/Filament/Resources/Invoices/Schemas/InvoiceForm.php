<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pilih Anggota HMIF (Opsional)')
                    ->options(User::pluck('nama', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $user = User::find($state);
                            if ($user) {
                                $set('nama_penerima', $user->nama);
                                $set('email_penerima', $user->email);
                            }
                        }
                    })
                    ->helperText('Pilih anggota untuk mengisi Nama & Email otomatis, atau isi manual di bawah jika untuk pihak luar.'),

                TextInput::make('nomor_invoice')
                    ->label('Nomor Invoice')
                    ->default(fn () => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4)))
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama_penerima')
                    ->label('Nama Penerima')
                    ->required(),

                TextInput::make('email_penerima')
                    ->label('Email Penerima')
                    ->email()
                    ->required(),

                TextInput::make('judul')
                    ->label('Keperluan / Deskripsi Tagihan')
                    ->placeholder('Contoh: Uang Kas Bulanan, Pelunasan Jaket, Sponsorship')
                    ->required(),

                TextInput::make('nominal')
                    ->label('Nominal Pembayaran (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->required(),

                DatePicker::make('tanggal')
                    ->label('Tanggal Terbit')
                    ->default(now())
                    ->required(),

                DatePicker::make('jatuh_tempo')
                    ->label('Batas Waktu (Jatuh Tempo)')
                    ->nullable(),

                Select::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'draft' => 'Draft',
                        'terkirim' => 'Terkirim / Menunggu Pembayaran',
                        'lunas' => 'Lunas',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->default('draft')
                    ->required(),

                Textarea::make('catatan')
                    ->label('Catatan / Instruksi Pembayaran (No. Rekening)')
                    ->placeholder("Contoh:\nTransfer ke Rekening Bank Mandiri 131001234567 a.n HMIF UNIKOM\nKonfirmasi bukti transfer ke WA Bendahara: 081234567890")
                    ->columnSpanFull(),
            ]);
    }
}
