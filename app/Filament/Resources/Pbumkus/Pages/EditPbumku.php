<?php

namespace App\Filament\Resources\Pbumkus\Pages;

use App\Filament\Resources\Pbumkus\PbumkuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPbumku extends EditRecord
{
    protected static string $resource = PbumkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }
}
