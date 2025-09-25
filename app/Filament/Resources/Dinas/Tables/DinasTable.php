<?php

namespace App\Filament\Resources\Dinas\Tables;

use App\Filament\Resources\Dinas\Schemas\DinasForm;
use App\Models\Dinas;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DinasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(false)
                    ->searchable(false),
                TextColumn::make('nama')
                    ->label('Nama Dinas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->recordActions([
                Action::make('edit_dinas')
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form(DinasForm::getComponents())
                    ->fillForm(function (Dinas $record): array {
                        return [
                            'nama' => $record->nama
                        ];
                    })
                    ->action(function (Dinas $record, array $data): void {
                        $record->update($data);
                    })
                    ->modalHeading('Ubah Dinas')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),
                Action::make('delete_dinas')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('Apakah Anda yakin ingin menghapus Dinas ini?')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Dinas $record): void {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Massal'),
                ]),
            ])
            ->toolbarActions([
                Action::make('tambah_dinas')
                    ->label('Tambah Dinas')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->form(DinasForm::getComponents())
                    ->action(function (array $data): void {
                        Dinas::create($data);
                    })
                    ->modalHeading('Tambah Dinas')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),
            ]);
    }
}
