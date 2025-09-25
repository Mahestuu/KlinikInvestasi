<?php

namespace App\Filament\Resources\PersyaratanPerizinans;

use App\Filament\Resources\PersyaratanPerizinans\Pages\CreatePersyaratanPerizinan;
use App\Filament\Resources\PersyaratanPerizinans\Pages\EditPersyaratanPerizinan;
use App\Filament\Resources\PersyaratanPerizinans\Pages\ListPersyaratanPerizinans;
use App\Filament\Resources\PersyaratanPerizinans\Schemas\PersyaratanPerizinanForm;
use App\Filament\Resources\PersyaratanPerizinans\Tables\PersyaratanPerizinansTable;
use App\Models\PersyaratanPerizinan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;


class PersyaratanPerizinanResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Manajemen KBLI';

    protected static ?string $model = PersyaratanPerizinan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'PersyaratanPerizinan';
    protected static ?string $navigationLabel = 'Persyaratan Perizinan KBLI';
    protected static ?string $modelLabel = 'Persyaratan KBLI';
    protected static ?string $pluralModelLabel = 'Persyaratan Perizinan KBLI';

    protected static ?int $navigationSort = 3; // Urutan dalam grup

    public static function form(Schema $schema): Schema
    {
        return PersyaratanPerizinanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersyaratanPerizinansTable::configure($table);
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
            'index' => ListPersyaratanPerizinans::route('/'),
            // 'create' => CreatePersyaratanPerizinan::route('/create'),
            // 'edit' => EditPersyaratanPerizinan::route('/{record}/edit'),
        ];
    }
}
