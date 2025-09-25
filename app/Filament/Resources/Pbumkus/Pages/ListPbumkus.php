<?php

namespace App\Filament\Resources\Pbumkus\Pages;

use App\Filament\Resources\Pbumkus\PbumkuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPbumkus extends ListRecords
{
    protected static string $resource = PbumkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
