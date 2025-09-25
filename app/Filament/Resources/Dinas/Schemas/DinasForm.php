<?php

namespace App\Filament\Resources\Dinas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DinasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Dinas')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama dinas'),
            ]);
    }

    public static function getComponents(): array
    {
        return [
            TextInput::make('nama')
                ->label('Nama Dinas')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan nama dinas'),
        ];
    }
}