<?php

namespace App\Filament\Resources\Kblis;

use App\Filament\Resources\Kblis\Pages\CreateKbli;
use App\Filament\Resources\Kblis\Pages\EditKbli;
use App\Filament\Resources\Kblis\Pages\ListKblis;
use App\Filament\Resources\Kblis\Schemas\KbliForm;
use App\Filament\Resources\Kblis\Tables\KblisTable;
use App\Models\Kbli;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KbliResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Manajemen KBLI';

    protected static ?string $model = Kbli::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ListBullet;

    protected static ?string $recordTitleAttribute = 'Kbli';
    protected static ?string $navigationLabel = 'Daftar KBLI';
    protected static ?string $modelLabel = 'KBLI';
    protected static ?string $pluralModelLabel = 'Daftar KBLI';

    public static function form(Schema $schema): Schema
    {
        return KbliForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KblisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKblis::route('/'),
            // 'create' => CreateKbli::route('/create'),
            // 'edit' => EditKbli::route('/{record}/edit'),
        ];
    }
}
