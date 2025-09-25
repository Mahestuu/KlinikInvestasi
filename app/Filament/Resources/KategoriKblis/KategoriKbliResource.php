<?php

namespace App\Filament\Resources\KategoriKblis;

use App\Filament\Resources\KategoriKblis\Pages\CreateKategoriKbli;
use App\Filament\Resources\KategoriKblis\Pages\EditKategoriKbli;
use App\Filament\Resources\KategoriKblis\Pages\ListKategoriKblis;
use App\Filament\Resources\KategoriKblis\Schemas\KategoriKbliForm;
use App\Filament\Resources\KategoriKblis\Tables\KategoriKblisTable;
use App\Models\KategoriKbli;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
use UnitEnum;

class KategoriKbliResource extends Resource
{
     protected static string | UnitEnum | null $navigationGroup = 'Manajemen KBLI';

    protected static ?string $model = KategoriKbli::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'KategoriKbli';
    protected static ?string $navigationLabel = 'Kategori KBLI';
    protected static ?string $modelLabel = 'Kategori KBLI';
    protected static ?string $pluralModelLabel = 'Kategori KBLI';

    public static function form(Schema $schema): Schema
    {
        return KategoriKbliForm::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return KategoriKblisTable::configure($table);
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
            'index' => ListKategoriKblis::route('/'),
            // 'create' => CreateKategoriKbli::route('/create'),
            // 'edit' => EditKategoriKbli::route('/{record}/edit'),
        ];
    }
}
