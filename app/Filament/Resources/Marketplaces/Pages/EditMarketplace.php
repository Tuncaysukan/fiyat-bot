<?php

namespace App\Filament\Resources\Marketplaces\Pages;

use App\Filament\Resources\Marketplaces\MarketplaceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMarketplace extends EditRecord
{
    protected static string $resource = MarketplaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
