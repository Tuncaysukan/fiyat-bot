<?php

namespace App\Filament\Resources\Marketplaces\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class MarketplacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Marketplace Adı')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('slug')
                    ->label('Slug')
                    ->color('primary'),

                TextColumn::make('base_url')
                    ->label('Ana URL')
                    ->limit(50)
                    ->copyable(),

                TextColumn::make('products_count')
                    ->label('Ürün Sayısı')
                    ->counts('products')
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
