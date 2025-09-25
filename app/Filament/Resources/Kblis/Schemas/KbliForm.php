<?php

namespace App\Filament\Resources\Kblis\Schemas;

use App\Models\KategoriKbli;
use App\Models\Dinas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KbliForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategorikbli_id')
                    ->label('Kategori KBLI')
                    ->options(KategoriKbli::pluck('nama', 'kategorikbli_id'))
                    ->required()
                    ->searchable(),
                Select::make('dinas_id')
                    ->label('Dinas')
                    ->options(Dinas::pluck('nama', 'dinas_id'))
                    ->required()
                    ->searchable(),
                TextInput::make('kode')
                    ->label('Kode KBLI')
                    ->required()
                    ->maxLength(10)
                    ->placeholder('Masukkan kode KBLI (contoh: 12345)'),
                TextInput::make('nama')
                    ->label('Nama KBLI')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama KBLI'),
                Textarea::make('ruang_lingkup')
                    ->label('Ruang Lingkup')
                    ->required()
                    ->placeholder('Masukkan ruang lingkup KBLI'),
            ]);
    }

    public static function getComponents(): array
    {
        return [
            Select::make('kategorikbli_id')
                ->label('Kategori KBLI')
                ->options(KategoriKbli::pluck('nama', 'kategorikbli_id'))
                ->required()
                ->searchable(),
            Select::make('dinas_id')
                ->label('Dinas')
                ->options(Dinas::pluck('nama', 'dinas_id'))
                ->required()
                ->searchable(),
            TextInput::make('kode')
                ->label('Kode KBLI')
                ->required()
                ->maxLength(10)
                ->placeholder('Masukkan kode KBLI (contoh: 12345)'),
            TextInput::make('nama')
                ->label('Nama KBLI')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan nama KBLI'),
            Textarea::make('ruang_lingkup')
                ->label('Ruang Lingkup')
                ->required()
                ->placeholder('Masukkan ruang lingkup KBLI'),
        ];
    }
}
