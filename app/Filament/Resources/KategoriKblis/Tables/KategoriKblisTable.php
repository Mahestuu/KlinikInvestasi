<?php

namespace App\Filament\Resources\KategoriKblis\Tables;

use App\Filament\Resources\KategoriKblis\Schemas\KategoriKbliForm;
use App\Models\KategoriKbli;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;

class KategoriKblisTable
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
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('edit_kategori')
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form(KategoriKbliForm::getComponents())
                    ->fillForm(function (KategoriKbli $record): array {
                        return [
                            'nama' => $record->nama,
                        ];
                    })
                    ->action(function (KategoriKbli $record, array $data): void {
                        $record->update($data);
                    })
                    ->modalHeading('Ubah Kategori KBLI')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),

                Action::make('delete_kategori')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('Apakah Anda yakin ingin menghapus Kategori ini?')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (KategoriKbli $record): void {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Massal'),
                ]),
            ])
            ->toolbarActions([
                Action::make('tambah_kategori')
                    ->label('Tambah Kategori KBLI')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->form(KategoriKbliForm::getComponents())
                    ->action(function (array $data): void {
                        KategoriKbli::create($data);
                    })
                    ->modalHeading('Tambah Kategori KBLI')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),
            ]);
        // ->recordActions([
        //     EditAction::make(),
        // ])
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}
