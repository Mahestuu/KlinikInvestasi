<?php

namespace App\Filament\Resources\PersyaratanPbumkus\Pages;

use App\Filament\Resources\PersyaratanPbumkus\PersyaratanPbumkuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersyaratanPbumku extends EditRecord
{
    protected static string $resource = PersyaratanPbumkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }
}
