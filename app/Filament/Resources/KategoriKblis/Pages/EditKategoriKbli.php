<?php

namespace App\Filament\Resources\KategoriKblis\Pages;

use App\Filament\Resources\KategoriKblis\KategoriKbliResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriKbli extends EditRecord
{
    protected static string $resource = KategoriKbliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
