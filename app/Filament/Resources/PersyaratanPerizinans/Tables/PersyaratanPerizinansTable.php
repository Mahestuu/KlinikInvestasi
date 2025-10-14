<?php

namespace App\Filament\Resources\PersyaratanPerizinans\Tables;

use App\Filament\Resources\PersyaratanPerizinans\Schemas\PersyaratanPerizinanForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\PersyaratanPerizinan;
use Illuminate\Database\Eloquent\Builder;



class PersyaratanPerizinansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('kbli_id')
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
                TextColumn::make('sub_poin_details_count')
                    ->label('Jumlah Turunan')
                    ->getStateUsing(function ($record) {
                        return $record->subpoin->sum(fn($subpoin) => $subpoin->details->count());
                    })
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
                                ->with('subpoin.details')
                                ->get()
                                ->map(fn($persyaratan) => [
                                    'nama' => $persyaratan->nama,
                                    'subpoin' => $persyaratan->subpoin->map(fn($subpoin) => [
                                        'item' => $subpoin->item,
                                        'details' => $subpoin->details->map(fn($detail) => [
                                            'text' => $detail->text,
                                        ])->toArray(),
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
                            foreach ($persyaratanData['subpoin'] as $subpoinData) {
                                $subpoin = $persyaratan->subpoin()->create([
                                    'item' => $subpoinData['item'],
                                ]);
                                if (!empty($subpoinData['details'])) {
                                    foreach ($subpoinData['details'] as $detail) {
                                        $subpoin->details()->create([
                                            'text' => $detail['text'],
                                        ]);
                                    }
                                }
                            }
                        }
                    })
                    ->successNotificationTitle('Data berhasil disimpan')
                    ->modalHeading('Ubah Persyaratan')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('8xl'),
                Action::make('delete_persyaratan')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalDescription('Apakah Anda yakin ingin menghapus persyaratan ini?')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (PersyaratanPerizinan $record): void {
                        $record->delete();
                    })
                    ->successNotificationTitle('Data berhasil dihapus'),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Massal')
                        ->successNotificationTitle('Data berhasil dihapus'),

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
                            foreach ($persyaratanData['subpoin'] as $subpoinData) {
                                $subpoin = $persyaratan->subpoin()->create([
                                    'item' => $subpoinData['item'],
                                ]);
                                if (!empty($subpoinData['details'])) {
                                    foreach ($subpoinData['details'] as $detail) {
                                        $subpoin->details()->create([
                                            'text' => $detail['text'],
                                        ]);
                                    }
                                }
                            }
                        }
                    })
                    ->successNotificationTitle('Data berhasil disimpan')
                    ->modalHeading('Tambah Persyaratan Perizinan KBLI')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->modalWidth('8xl'),
            ]);
    }
}
