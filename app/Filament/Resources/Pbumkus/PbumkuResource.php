<?php

namespace App\Filament\Resources\Pbumkus;

use App\Filament\Resources\Pbumkus\Pages\CreatePbumku;
use App\Filament\Resources\Pbumkus\Pages\EditPbumku;
use App\Filament\Resources\Pbumkus\Pages\ListPbumkus;
use App\Filament\Resources\Pbumkus\Schemas\PbumkuForm;
use App\Filament\Resources\Pbumkus\Tables\PbumkusTable;
use App\Models\Pbumku;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PbumkuResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Manajemen PB UMKU';

    protected static ?string $model = Pbumku::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ListBullet;

    protected static ?string $recordTitleAttribute = 'Pbumku';
    protected static ?string $navigationLabel = 'Daftar PB UMKU';
    protected static ?string $modelLabel = 'PB UMKU';
    protected static ?string $pluralModelLabel = 'Daftar PB UMKU';

    public static function form(Schema $schema): Schema
    {
        return PbumkuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PbumkusTable::configure($table);
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
            'index' => ListPbumkus::route('/'),
            // 'create' => CreatePbumku::route('/create'),
            // 'edit' => EditPbumku::route('/{record}/edit'),
        ];
    }
}
