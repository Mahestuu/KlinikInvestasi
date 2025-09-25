<?php

namespace App\Filament\Resources\Dinas;

use App\Filament\Resources\Dinas\Pages\CreateDinas;
use App\Filament\Resources\Dinas\Pages\EditDinas;
use App\Filament\Resources\Dinas\Pages\ListDinas;
use App\Filament\Resources\Dinas\Schemas\DinasForm;
use App\Filament\Resources\Dinas\Tables\DinasTable;
use App\Models\Dinas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;



class DinasResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Dinas';

    protected static ?string $model = Dinas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingLibrary ;

    protected static ?string $recordTitleAttribute = 'Dinas';
    protected static ?string $navigationLabel = 'Daftar Dinas';
    protected static ?string $modelLabel = 'Daftar Dinas';
    protected static ?string $pluralModelLabel = 'Daftar Dinas';
    // protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return DinasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DinasTable::configure($table);
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
            'index' => ListDinas::route('/'),
            // 'create' => CreateDinas::route('/create'),
            // 'edit' => EditDinas::route('/{record}/edit'),
        ];
    }
}
