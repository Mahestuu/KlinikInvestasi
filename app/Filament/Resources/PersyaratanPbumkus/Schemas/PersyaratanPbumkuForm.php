<?php

namespace App\Filament\Resources\PersyaratanPbumkus\Schemas;

use App\Models\Pbumku;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;

class PersyaratanPbumkuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pbumku_id')
                    ->label('Pbumku')
                    ->options(Pbumku::pluck('nama', 'pbumku_id'))
                    ->required()
                    ->searchable(),
                Repeater::make('persyaratan')
                    ->label('Persyaratan Pbumku')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Persyaratan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama persyaratan'),
                        Repeater::make('subpoin')
                            ->label('Sub-Poin')
                            ->schema([
                                Textarea::make('item')
                                    ->label('Item Sub-Poin')
                                    ->required()
                                    ->placeholder('Masukkan item sub-poin'),
                            ])
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['item'] ?? null)
                            ->addActionLabel('Tambah Sub-Poin')
                            ->deleteAction(fn($action) => $action->requiresConfirmation())
                            ->grid(2),
                    ])
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => $state['nama'] ?? null)
                    ->addActionLabel('Tambah Persyaratan Pbumku')
                    ->deleteAction(fn($action) => $action->requiresConfirmation())
                    ->grid(2),
            ]);
    }

    public static function getComponents(): array
    {
        return [
            Select::make('pbumku_id')
                ->label('Pbumku')
                ->options(Pbumku::pluck('nama', 'pbumku_id'))
                ->required()
                ->searchable(),
            Repeater::make('persyaratan')
                ->label('Persyaratan Pbumku')
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Persyaratan')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan nama persyaratan'),
                    Repeater::make('subpoin')
                        ->label('Sub-Poin')
                        ->schema([
                            Textarea::make('item')
                                ->label('Item Sub-Poin')
                                ->required()
                                ->rows(10)
                                ->cols(10)
                                ->placeholder('Masukkan item sub-poin'),
                        ])
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['item'] ?? null)
                        ->addActionLabel('Tambah Sub-Poin')
                        ->deleteAction(fn($action) => $action->requiresConfirmation())
                        ->grid(2),
                ])
                ->collapsible()
                ->itemLabel(fn(array $state): ?string => $state['nama'] ?? null)
                ->addActionLabel('Tambah Persyaratan Pbumku')
                ->deleteAction(fn($action) => $action->requiresConfirmation())
                ->grid(2),
        ];
    }
}
