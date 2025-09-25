<?php

namespace App\Filament\Resources\KategoriKblis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KategoriKbliForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama kategori KBLI'),
            ]);
    }
    public static function getComponents(): array
    {
        return [
            TextInput::make('nama')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan nama kategori KBLI'),
        ];
    }
}