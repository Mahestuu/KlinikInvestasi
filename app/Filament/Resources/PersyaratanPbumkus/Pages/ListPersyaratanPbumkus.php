<?php

namespace App\Filament\Resources\PersyaratanPbumkus\Pages;

use App\Filament\Resources\PersyaratanPbumkus\PersyaratanPbumkuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersyaratanPbumkus extends ListRecords
{
    protected static string $resource = PersyaratanPbumkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
