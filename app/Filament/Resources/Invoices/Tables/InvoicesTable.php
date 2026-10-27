<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_invoice')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nama_penerima')
                    ->label('Penerima')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email_penerima')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('judul')
                    ->label('Keperluan')
                    ->searchable()
                    ->limit(25),

                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'terkirim' => 'warning',
                        'lunas' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('terakhir_dikirim_at')
                    ->label('Terakhir Dikirim')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum pernah')
                    ->toggleable(),
            ])
            ->recordActions([
                Action::make('kirimEmail')
                    ->label('Kirim Email')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Invoice ke Email')
                    ->modalDescription(fn (Invoice $record): string => "Kirim invoice {$record->nomor_invoice} sebesar Rp " . number_format($record->nominal, 0, ',', '.') . " ke {$record->email_penerima}?")
                    ->modalSubmitActionLabel('Ya, Kirim Sekarang')
                    ->action(function (Invoice $record) {
                        try {
                            Mail::to($record->email_penerima)->send(new InvoiceMail($record));

                            $record->update([
                                'status' => $record->status === 'draft' ? 'terkirim' : $record->status,
                                'terakhir_dikirim_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Invoice Berhasil Dikirim')
                                ->body("Email tagihan telah dikirim ke {$record->email_penerima}.")
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Gagal Mengirim Email')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
