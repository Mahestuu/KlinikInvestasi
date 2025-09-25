<?php

namespace App\Filament\Resources\PersyaratanPerizinans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use App\Models\Kbli;
use Filament\Forms\Form;

class PersyaratanPerizinanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Select::make('kbli_id')
                    ->label('KBLI')
                    ->options(Kbli::pluck('nama', 'kbli_id'))
                    ->required()
                    ->searchable()
                    ->columnSpan(6),
                Repeater::make('persyaratan')
                    ->label('Persyaratan Perizinan')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Persyaratan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama persyaratan')
                            ->columnSpan('full'),
                        Repeater::make('subpoin')
                            ->label('Sub-Persyaratan Perizinan')
                            ->schema([
                                Textarea::make('item')
                                    ->label('Item Sub-Persyaratan Perizinan')
                                    ->required()
                                    ->placeholder('Masukkan item sub persyaratan perizinan'),
                            ])
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['item'] ?? null)
                            ->addActionLabel('Tambah Sub Persyaratan Perizinan KBLI')
                            ->deleteAction(fn($action) => $action->requiresConfirmation())
                            ->grid(2)
                            ->columnSpan('full'),
                    ])
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => $state['nama'] ?? null)
                    ->addActionLabel('Tambah Persyaratan Perizinan KBLI')
                    ->deleteAction(fn($action) => $action->requiresConfirmation())
                    ->grid(2)
                    ->columnSpan('full'),
            ]);
    }

    public static function getComponents(): array
    {
        return [
            Select::make('kbli_id')
                ->label('KBLI')
                ->options(Kbli::pluck('nama', 'kbli_id'))
                ->required()
                ->searchable()
                ->columnSpan(4),
            Repeater::make('persyaratan')
                ->label('Persyaratan Perizinan')
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Persyaratan')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan nama persyaratan')
                        ->columnSpan('full'),
                    Repeater::make('subpoin')
                        ->label('Sub-Persyaratan Perizinan (Jika Sub-Persyaratan Perizinan kosong, Dihapus)')
                        ->schema([
                            Textarea::make('item')
                                ->rows(10)
                                ->cols(10)
                                ->label('Item Sub-Persyaratan Perizinan')
                                ->required()
                                ->placeholder('Masukkan item sub persyaratan perizinan'),
                        ])
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['item'] ?? null)
                        ->addActionLabel('Tambah Sub-Persyaratan Perizinan KBLI')
                        ->deleteAction(fn($action) => $action->requiresConfirmation())
                        ->grid(2)
                        ->columnSpan('full'),
                ])
                ->collapsible()
                ->itemLabel(fn(array $state): ?string => $state['nama'] ?? null)
                ->addActionLabel('Tambah Persyaratan Perizinan KBLI')
                ->deleteAction(fn($action) => $action->requiresConfirmation())
                ->grid(2)
                ->columnSpan('full'),
        ];
    }
}
