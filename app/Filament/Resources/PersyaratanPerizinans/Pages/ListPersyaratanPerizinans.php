<?php

namespace App\Filament\Resources\PersyaratanPerizinans\Pages;

use App\Filament\Resources\PersyaratanPerizinans\PersyaratanPerizinanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersyaratanPerizinans extends ListRecords
{
    protected static string $resource = PersyaratanPerizinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
