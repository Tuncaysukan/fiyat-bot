<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Marketplace;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('marketplace_id')
                    ->label('Marketplace')
                    ->options(Marketplace::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('title')
                    ->label('Ürün Adı')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('url')
                    ->label('Ürün URL')
                    ->required()
                    ->url()
                    ->columnSpanFull(),

                Select::make('parse_strategy')
                    ->label('Parse Stratejisi')
                    ->options([
                        'selector' => 'CSS Selector',
                        'ldjson' => 'JSON-LD',
                        'api' => 'API',
                        'custom' => 'Custom',
                    ])
                    ->default('selector')
                    ->required(),

                TextInput::make('selector')
                    ->label('CSS Selector')
                    ->placeholder('Örn: .price-current, [data-price]')
                    ->helperText('Fiyat elementini seçmek için CSS selector'),

                TextInput::make('currency')
                    ->label('Para Birimi')
                    ->default('TRY')
                    ->maxLength(8)
                    ->required(),

                TextInput::make('last_price')
                    ->label('Son Fiyat')
                    ->numeric()
                    ->step(0.01)
                    ->prefix('₺'),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
