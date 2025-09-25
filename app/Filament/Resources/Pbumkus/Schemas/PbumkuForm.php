<?php

namespace App\Filament\Resources\Pbumkus\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Kbli;
use App\Models\Dinas;
use Filament\Forms\Components\Repeater;

class PbumkuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('dinas_id')
                    ->label('Dinas')
                    ->options(Dinas::pluck('nama', 'dinas_id'))
                    ->required()
                    ->searchable(),
                TextInput::make('nama')
                    ->label('Nama Pbumku')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama pbumku'),
                Repeater::make('kbli')
                    ->label('KBLI')
                    ->schema([
                        Select::make('kbli_id')
                            ->label('Pilih Kode KBLI')
                            ->options(Kbli::pluck('kode', 'kbli_id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => Kbli::find($state['kbli_id'])?->kode ?? null)
                    ->addActionLabel('Tambah Kode KBLI')
                    ->deleteAction(fn($action) => $action->requiresConfirmation())
                    ->grid(2),
            ]);
    }

    public static function getComponents(): array
    {
        return [
            Select::make('dinas_id')
                ->label('Dinas')
                ->options(Dinas::pluck('nama', 'dinas_id'))
                ->required()
                ->searchable(),
            TextInput::make('nama')
                ->label('Nama Pbumku')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan nama pbumku'),
            Repeater::make('kbli')
                ->label('KBLI (Jika KBLI Tidak Ingin Di Isi Dihapus)')
                ->schema([
                    Select::make('kbli_id')
                        ->label('Pilih Kode KBLI')
                        ->options(Kbli::pluck('kode', 'kbli_id'))
                        ->required()
                        ->searchable(),
                ])
                ->collapsible()
                ->itemLabel(fn(array $state): ?string => Kbli::find($state['kbli_id'])?->kode ?? null)
                ->addActionLabel('Tambah Kode KBLI')
                ->deleteAction(fn($action) => $action->requiresConfirmation())
                ->grid(2),
        ];
    }
}
