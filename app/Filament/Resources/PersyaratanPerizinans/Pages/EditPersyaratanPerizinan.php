<?php

namespace App\Filament\Resources\PersyaratanPerizinans\Pages;

use App\Filament\Resources\PersyaratanPerizinans\PersyaratanPerizinanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersyaratanPerizinan extends EditRecord
{
    protected static string $resource = PersyaratanPerizinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
