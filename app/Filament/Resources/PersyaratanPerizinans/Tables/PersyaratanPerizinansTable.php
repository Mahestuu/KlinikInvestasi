<?php

namespace App\Filament\Resources\PersyaratanPerizinans\Tables;

use App\Filament\Resources\PersyaratanPerizinans\Schemas\PersyaratanPerizinanForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\PersyaratanPerizinan;
use Illuminate\Database\Eloquent\Builder;


class PersyaratanPerizinansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('kbli_id') // Pastikan pengurutan berdasarkan kbli_id
            ->columns([
                TextColumn::make('kbli_row_number')
                    ->label('No')
                    ->getStateUsing(function ($record, $rowLoop) {
                        static $kbliIds = [];
                        static $index = 0;
                        static $lastKbliId = null;

                        $kbliId = $record->kbli_id;

                        if ($kbliId !== $lastKbliId) {
                            $kbliIds[$kbliId] = ++$index;
                            $lastKbliId = $kbliId;
                            return $kbliIds[$kbliId];
                        }

                        return '';
                    })
                    ->sortable(query: fn(Builder $query) => $query->orderBy('kbli_id'))
                    ->searchable(false)
                    ->alignCenter(),
                TextColumn::make('kbli.nama')
                    ->label('KBLI')
                    ->getStateUsing(function ($record, $rowLoop) {
                        static $lastKbliId = null;

                        $kbliId = $record->kbli_id;

                        if ($kbliId !== $lastKbliId) {
                            $lastKbliId = $kbliId;
                            return $record->kbli->nama;
                        }

                        return '';
                    })
                    ->sortable(query: fn(Builder $query) => $query->orderBy('kbli_id'))
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Persyaratan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subpoin_count')
                    ->label('Jumlah Sub Persyaratan')
                    ->counts('subpoin')
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
                Action::make('edit_persyaratan')
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form(PersyaratanPerizinanForm::getComponents())
                    ->fillForm(function (PersyaratanPerizinan $record): array {
                        return [
                            'kbli_id' => $record->kbli_id,
                            'persyaratan' => PersyaratanPerizinan::where('kbli_id', $record->kbli_id)
                                ->get()
                                ->map(fn($persyaratan) => [
                                    'nama' => $persyaratan->nama,
                                    'subpoin' => $persyaratan->subpoin->map(fn($subpoin) => [
                                        'item' => $subpoin->item,
                                    ])->toArray(),
                                ])->toArray(),
                        ];
                    })
                    ->action(function (array $data): void {
                        $kbliId = $data['kbli_id'];
                        PersyaratanPerizinan::where('kbli_id', $kbliId)->delete();
                        foreach ($data['persyaratan'] as $persyaratanData) {
                            $persyaratan = PersyaratanPerizinan::create([
                                'kbli_id' => $kbliId,
                                'nama' => $persyaratanData['nama'],
                            ]);
                            foreach ($persyaratanData['subpoin'] as $subpoin) {
                                $persyaratan->subpoin()->create(['item' => $subpoin['item']]);
                            }
                        }
                    })
                    ->modalHeading('Ubah Persyaratan')
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
                    ->action(function (PersyaratanPerizinan $record): void {
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
                    ->form(PersyaratanPerizinanForm::getComponents())
                    ->action(function (array $data): void {
                        foreach ($data['persyaratan'] as $persyaratanData) {
                            $persyaratan = PersyaratanPerizinan::create([
                                'kbli_id' => $data['kbli_id'],
                                'nama' => $persyaratanData['nama'],
                            ]);
                            foreach ($persyaratanData['subpoin'] as $subpoin) {
                                $persyaratan->subpoin()->create(['item' => $subpoin['item']]);
                            }
                        }
                    })
                    ->modalHeading('Tambah Persyaratan Perizinan KBLI')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('8xl'),
            ]);
    }
}
