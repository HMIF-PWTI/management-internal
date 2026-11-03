<?php

namespace App\Filament\Resources\LkmmPendaftarans\Tables;

use App\Filament\Resources\LkmmPendaftarans\Pages\ViewLkmmPendaftaran;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LkmmPendaftaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->columns([
                TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('angkatan')
                    ->label('Angkatan')
                    ->sortable(),

                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('ipk')
                    ->label('IPK')
                    ->sortable(),

                TextColumn::make('no_wa')
                    ->label('No. WhatsApp')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'diterima' => 'success',
                        'ditolak'  => 'danger',
                        default    => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak'  => 'Ditolak',
                    ]),
                SelectFilter::make('angkatan')
                    ->label('Angkatan')
                    ->options([
                        '2024' => '2024',
                        '2025' => '2025',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn ($record) => ViewLkmmPendaftaran::getUrl(['record' => $record]))
            ->defaultSort('created_at', 'desc');
    }
}
