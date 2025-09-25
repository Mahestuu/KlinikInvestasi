<?php

namespace App\Filament\Resources\Dinas\Pages;

use App\Filament\Resources\Dinas\DinasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDinas extends EditRecord
{
    protected static string $resource = DinasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
