<?php

namespace App\Filament\Resources\Divisis\Tables;

use App\Models\Divisi;
use App\Models\Fitur;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DivisisTable
{
    public static function configure(Table $table): Table
    {
        $fiturColumns = Fitur::query()
            ->orderBy('nama')
            ->get()
            ->map(function (Fitur $fitur): ToggleColumn {
                return ToggleColumn::make("fitur_{$fitur->id}")
                    ->label($fitur->nama)

                    // Mengecek apakah divisi memiliki fitur tersebut.
                    ->getStateUsing(
                        fn (Divisi $record): bool =>
                            $record->fiturs()
                                ->whereKey($fitur->id)
                                ->exists()
                    )

                    // Saat tombol diubah.
                    ->updateStateUsing(
                        function (
                            Divisi $record,
                            bool $state
                        ) use ($fitur): void {
                            if ($state) {
                                $record->fiturs()
                                    ->syncWithoutDetaching([
                                        $fitur->id,
                                    ]);
                            } else {
                                $record->fiturs()
                                    ->detach($fitur->id);
                            }
                        }
                    );
            })
            ->all();

        return $table
            ->poll('3s')
            ->columns([
                TextColumn::make('nama')
                    ->label('Divisi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('singkatan')
                    ->label('Singkatan')
                    ->badge()
                    ->searchable(),

                ...$fiturColumns,
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('nama');
    }
}