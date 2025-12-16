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
use Illuminate\Database\Eloquent\Builder;


class PersyaratanPbumkusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('pbumku_id')
            ->columns([
                TextColumn::make('pbumku_row_number')
                    ->label('No')
                    ->getStateUsing(function ($record, $rowLoop) {
                        static $pbumkuIds = [];
                        static $index = 0;
                        static $lastPbumkuId = null;

                        $pbumkuId = $record->pbumku_id;

                        if ($pbumkuId !== $lastPbumkuId) {
                            $pbumkuIds[$pbumkuId] = ++$index;
                            $lastPbumkuId = $pbumkuId;
                            return $pbumkuIds[$pbumkuId];
                        }

                        return '';
                    })
                    ->sortable(query: fn(Builder $query) => $query->orderBy('pbumku_id'))
                    ->searchable(false)
                    ->alignCenter(),
                TextColumn::make('pbumku.nama')
                    ->label('Pbumku')
                    ->getStateUsing(function ($record, $rowLoop) {
                        static $lastPbumkuId = null;

                        $pbumkuId = $record->pbumku_id;

                        if ($pbumkuId !== $lastPbumkuId) {
                            $lastPbumkuId = $pbumkuId;
                            return $record->pbumku->nama;
                        }

                        return '';
                    })
                    ->sortable(query: fn(Builder $query) => $query->orderBy('pbumku_id'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Persyaratan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
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
                                ->with('subpoinPbumku.details')
                                ->get()
                                ->map(fn($persyaratan) => [
                                    'nama' => $persyaratan->nama,
                                    'subpoin' => $persyaratan->subpoinPbumku->map(fn($subpoin) => [
                                        'item' => $subpoin->item,
                                        'details' => $subpoin->details->map(fn($detail) => [
                                            'text' => $detail->text,
                                        ])->toArray(),
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
                                $subpoinRecord = $persyaratan->subpoinPbumku()->create(['item' => $subpoin['item']]);
                                // Simpan details jika ada
                                if (!empty($subpoin['details'])) {
                                    foreach ($subpoin['details'] as $detail) {
                                        $subpoinRecord->details()->create([
                                            'text' => $detail['text'],
                                        ]);
                                    }
                                }
                            }
                        }
                    })
                    ->modalHeading('Ubah Persyaratan Pbumku')
                    ->successNotificationTitle('Data berhasil disimpan')
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
                    })
                    ->successNotificationTitle('Data berhasil dihapus'),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Hapus Massal')
                        ->modalDescription(fn($records) => 'Apakah Anda yakin ingin menghapus ' . $records->count() . ' persyaratan yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->successNotificationTitle(fn($records) => $records->count() . ' persyaratan berhasil dihapus')
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->selectCurrentPageOnly(false) // Bisa select semua halaman, bukan hanya halaman saat ini
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
                                $subpoinRecord = $persyaratan->subpoinPbumku()->create(['item' => $subpoin['item']]);
                                // Simpan details jika ada
                                if (!empty($subpoin['details'])) {
                                    foreach ($subpoin['details'] as $detail) {
                                        $subpoinRecord->details()->create([
                                            'text' => $detail['text'],
                                        ]);
                                    }
                                }
                            }
                        }
                    })
                    ->successNotificationTitle('Data berhasil disimpan')
                    ->modalHeading('Tambah Persyaratan Perizinan PBUMKU')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('8xl'),
            ]);
    }
}
