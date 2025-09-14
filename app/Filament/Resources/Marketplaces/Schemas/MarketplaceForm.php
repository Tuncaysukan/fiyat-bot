<?php

namespace App\Filament\Resources\Marketplaces\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MarketplaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Marketplace Adı')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Örn: Teknosa, Hepsiburada'),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('teknosa, hepsiburada')
                    ->helperText('URL için kullanılacak benzersiz tanımlayıcı'),

                TextInput::make('base_url')
                    ->label('Ana URL')
                    ->url()
                    ->placeholder('https://www.teknosa.com')
                    ->helperText('Marketplace\'in ana web sitesi URL\'i'),
            ]);
    }
}
