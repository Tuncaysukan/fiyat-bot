<?php

namespace App\Filament\Resources\PriceHistories\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class PriceHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Ürün')
                    ->options(Product::all()->pluck('title', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Fiyat')
                    ->numeric()
                    ->step(0.01)
                    ->required()
                    ->prefix('₺')
                    ->columnSpan(1),

                DateTimePicker::make('created_at')
                    ->label('Tarih')
                    ->default(now())
                    ->required()
                    ->columnSpan(1),
            ]);
    }
}
