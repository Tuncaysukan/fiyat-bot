<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActiveProductsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Aktif Ürünler';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::with(['marketplace', 'rule'])
                    ->where('is_active', true)
                    ->latest('updated_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Ürün')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('marketplace.name')
                    ->label('Marketplace')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('last_price')
                    ->label('Son Fiyat')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rule.drop_percent')
                    ->label('Yüzde Kuralı')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state . '%' : '-')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('rule.drop_amount')
                    ->label('Tutar Kuralı')
                    ->formatStateUsing(fn ($state) => $state > 0 ? '₺' . number_format($state, 2) : '-')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\IconColumn::make('has_rule')
                    ->label('Kural Var')
                    ->getStateUsing(fn ($record) => $record->rule !== null)
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Son Güncelleme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
