<?php

namespace App\Filament\Resources\PersyaratanPbumkus;

use App\Filament\Resources\PersyaratanPbumkus\Pages\CreatePersyaratanPbumku;
use App\Filament\Resources\PersyaratanPbumkus\Pages\EditPersyaratanPbumku;
use App\Filament\Resources\PersyaratanPbumkus\Pages\ListPersyaratanPbumkus;
use App\Filament\Resources\PersyaratanPbumkus\Schemas\PersyaratanPbumkuForm;
use App\Filament\Resources\PersyaratanPbumkus\Tables\PersyaratanPbumkusTable;
use App\Models\PersyaratanPbumku;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PersyaratanPbumkuResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Manajemen PB UMKU';

    protected static ?string $model = PersyaratanPbumku::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'PersyaratanPbumku';
    protected static ?string $navigationLabel = 'Persyaratan Perizinan PB UMKU';
    protected static ?string $modelLabel = 'Persyaratan Perizinan PB UMKU';
    protected static ?string $pluralModelLabel = 'Persyaratan Perizinan PB UMKU';


    public static function form(Schema $schema): Schema
    {
        return PersyaratanPbumkuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersyaratanPbumkusTable::configure($table);
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
            'index' => ListPersyaratanPbumkus::route('/'),
            // 'create' => CreatePersyaratanPbumku::route('/create'),
            // 'edit' => EditPersyaratanPbumku::route('/{record}/edit'),
        ];
    }
}
