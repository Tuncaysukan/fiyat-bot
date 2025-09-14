<?php

namespace App\Filament\Resources\Marketplaces;

use App\Filament\Resources\Marketplaces\Pages\CreateMarketplace;
use App\Filament\Resources\Marketplaces\Pages\EditMarketplace;
use App\Filament\Resources\Marketplaces\Pages\ListMarketplaces;
use App\Filament\Resources\Marketplaces\Schemas\MarketplaceForm;
use App\Filament\Resources\Marketplaces\Tables\MarketplacesTable;
use App\Models\Marketplace;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MarketplaceResource extends Resource
{
    protected static ?string $model = Marketplace::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'marketplave';

    public static function form(Schema $schema): Schema
    {
        return MarketplaceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarketplacesTable::configure($table);
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
            'index' => ListMarketplaces::route('/'),
            'create' => CreateMarketplace::route('/create'),
            'edit' => EditMarketplace::route('/{record}/edit'),
        ];
    }
}
