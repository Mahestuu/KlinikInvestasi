<?php

namespace App\Filament\Resources\PersyaratanPbumkus\Tables;

use App\Filament\Resources\PersyaratanPbumkus\Schemas\PersyaratanPbumkuForm;
use App\Models\PersyaratanPbumku;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;


class PersyaratanPbumkusTable
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
                TextColumn::make('pbumku.nama')
                    ->label('Pbumku')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Persyaratan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subpoin_count')
                    ->label('Jumlah Sub-Poin')
                    ->getStateUsing(function ($record) {
                        $count = $record->subpoinPbumku()->count();
                        return $count > 0 ? $count : '0';
                    }),
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
                Action::make('edit_persyaratan')
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil')
                    ->color('warning') // Warna biru
                    ->form(PersyaratanPbumkuForm::getComponents())
                    ->fillForm(function (PersyaratanPbumku $record): array {
                        return [
                            'pbumku_id' => $record->pbumku_id,
                            'persyaratan' => PersyaratanPbumku::where('pbumku_id', $record->pbumku_id)
                                ->get()
                                ->map(fn($persyaratan) => [
                                    'nama' => $persyaratan->nama,
                                    'subpoin' => $persyaratan->subpoinPbumku->map(fn($subpoin) => [
                                        'item' => $subpoin->item,
                                    ])->toArray(),
                                ])->toArray(),
                        ];
                    })
                    ->action(function (array $data): void {
                        $pbumkuId = $data['pbumku_id'];
                        PersyaratanPbumku::where('pbumku_id', $pbumkuId)->delete();
                        foreach ($data['persyaratan'] as $persyaratanData) {
                            $persyaratan = PersyaratanPbumku::create([
                                'pbumku_id' => $pbumkuId,
                                'nama' => $persyaratanData['nama'],
                            ]);
                            foreach ($persyaratanData['subpoin'] as $subpoin) {
                                $persyaratan->subpoinPbumku()->create(['item' => $subpoin['item']]);
                            }
                        }
                    })
                    ->modalHeading('Ubah Persyaratan Pbumku')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('8xl'),
                Action::make('delete_persyaratan')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger') // Warna merah
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('Apakah Anda yakin ingin menghapus persyaratan ini?')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (PersyaratanPbumku $record): void {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Massal'),
                ]),
            ])
            ->toolbarActions([
                Action::make('tambah_persyaratan')
                    ->label('Tambah Persyaratan Perizinan')
                    ->icon('heroicon-o-plus')
                    ->form(PersyaratanPbumkuForm::getComponents())
                    ->action(function (array $data): void {
                        foreach ($data['persyaratan'] as $persyaratanData) {
                            $persyaratan = PersyaratanPbumku::create([
                                'pbumku_id' => $data['pbumku_id'],
                                'nama' => $persyaratanData['nama'],
                            ]);
                            foreach ($persyaratanData['subpoin'] as $subpoin) {
                                $persyaratan->subpoinPbumku()->create(['item' => $subpoin['item']]);
                            }
                        }
                    })
                    ->modalHeading('Tambah Persyaratan Perizinan PBUMKU')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('8xl'),
            ]);
    }
}
