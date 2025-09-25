<?php

namespace App\Filament\Resources\Kblis\Tables;

use App\Filament\Resources\Kblis\Schemas\KbliForm;
use App\Models\Kbli;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;


class KblisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Kbli::query()->with(['kategoriKbli', 'dinas']))
            ->columns([
                TextColumn::make('row_number')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(false)
                    ->searchable(false),
                TextColumn::make('kategoriKbli.nama')
                    ->label('Kategori KBLI')
                    ->getStateUsing(fn($record) => $record->kategoriKbli?->nama ?? '-')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                // ->maxWidth('200px'),
                TextColumn::make('dinas.nama')
                    ->label('Dinas')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('kode')
                    ->label('Kode KBLI')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama KBLI')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('ruang_lingkup')
                    ->label('Ruang Lingkup')
                    ->searchable()
                    ->wrap(),
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
                Action::make('edit_kbli')
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form(KbliForm::getComponents())
                    ->fillForm(function (Kbli $record): array {
                        return [
                            'kategorikbli_id' => $record->kategorikbli_id,
                            'dinas_id' => $record->dinas_id,
                            'kode' => $record->kode,
                            'nama' => $record->nama,
                            'ruang_lingkup' => $record->ruang_lingkup,
                        ];
                    })
                    ->action(function (Kbli $record, array $data): void {
                        $record->update($data);
                    })
                    ->modalHeading('Ubah KBLI')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),
                Action::make('delete_kbli')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger') // Warna merah untuk tombol Hapus
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('Apakah Anda yakin ingin menghapus KBLI ini?')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Kbli $record): void {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Massal'),
                ]),
            ])
            ->toolbarActions([
                Action::make('tambah_kbli')
                    ->label('Tambah KBLI')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->form(KbliForm::getComponents())
                    ->action(function (array $data): void {
                        Kbli::create($data);
                    })
                    ->modalHeading('Tambah KBLI')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),
            ]);
    }
}
