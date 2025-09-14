<?php

namespace App\Filament\Resources\PriceRules\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PriceRuleForm
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

                TextInput::make('drop_percent')
                    ->label('Yüzde Düşüş Eşiği (%)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(5.00)
                    ->suffix('%')
                    ->helperText('Fiyat bu yüzdeden fazla düştüğünde bildirim gönderilir')
                    ->columnSpan(1),

                TextInput::make('drop_amount')
                    ->label('Tutar Düşüş Eşiği (₺)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0.00)
                    ->prefix('₺')
                    ->helperText('Fiyat bu tutardan fazla düştüğünde bildirim gönderilir')
                    ->columnSpan(1),
            ]);
    }
}
