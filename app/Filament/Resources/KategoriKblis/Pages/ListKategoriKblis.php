<?php

namespace App\Filament\Resources\KategoriKblis\Pages;

use App\Filament\Resources\KategoriKblis\KategoriKbliResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKategoriKblis extends ListRecords
{
    protected static string $resource = KategoriKbliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
