<?php

namespace App\Filament\Resources\Pbumkus\Tables;

use App\Filament\Resources\Pbumkus\Schemas\PbumkuForm;
use App\Models\Dinas;
use App\Models\Pbumku;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;

class PbumkusTable
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
                TextColumn::make('dinas.nama')
                    ->label('Dinas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kbli_kode')
                    ->label('Kode KBLI')
                    ->getStateUsing(fn($record) => $record->kbli->pluck('kode')->join(', '))
                    ->searchable(query: fn($query, $search) => $query->whereHas('kbli', fn($q) => $q->where('kode', 'like', "%{$search}%")))
                    ->sortable(false),
                TextColumn::make('nama')
                    ->label('Nama Pbumku')
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
                Action::make('edit_pbumku')
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil')
                    ->color('warning') // Warna biru
                    ->form(PbumkuForm::getComponents())
                    ->fillForm(function (Pbumku $record): array {
                        return [
                            'dinas_id' => $record->dinas_id,
                            'nama' => $record->nama,
                            'kbli' => $record->kbli->map(fn($kbli) => ['kbli_id' => $kbli->kbli_id])->toArray(),
                        ];
                    })
                    ->action(function (Pbumku $record, array $data): void {
                        $record->update([
                            'dinas_id' => $data['dinas_id'],
                            'nama' => $data['nama']
                        ]);
                        $record->kbli()->sync(array_column($data['kbli'], 'kbli_id'));
                    })
                    ->modalHeading('Ubah Pbumku')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),
                Action::make('delete_pbumku')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger') // Warna merah
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('Apakah Anda yakin ingin menghapus pbumku ini?')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Pbumku $record): void {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Massal'),
                ]),
            ])
            ->toolbarActions([
                Action::make('tambah_pbumku')
                    ->label('Tambah Pbumku')
                    ->icon('heroicon-o-plus')
                    ->form(PbumkuForm::getComponents())
                    ->action(function (array $data): void {
                        $pbumku = Pbumku::create([
                            'dinas_id' => $data['dinas_id'],
                            'nama' => $data['nama'],
                        ]);
                        $pbumku->kbli()->sync(array_column($data['kbli'], 'kbli_id'));
                    })
                    ->modalHeading('Tambah Pbumku')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('lg'),
            ]);
    }
}
