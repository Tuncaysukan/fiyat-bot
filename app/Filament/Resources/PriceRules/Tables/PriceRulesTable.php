<?php

namespace App\Filament\Resources\PriceRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class PriceRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('product.title')
                    ->label('Ürün')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('product.marketplace.name')
                    ->label('Marketplace')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('drop_percent')
                    ->label('Yüzde Eşiği')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state . '%' : '-')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                TextColumn::make('drop_amount')
                    ->label('Tutar Eşiği')
                    ->formatStateUsing(fn ($state) => $state > 0 ? '₺' . number_format($state, 2) : '-')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                BadgeColumn::make('rule_type')
                    ->label('Kural Tipi')
                    ->formatStateUsing(function ($record) {
                        if ($record->drop_percent > 0 && $record->drop_amount > 0) {
                            return 'Her İkisi';
                        } elseif ($record->drop_percent > 0) {
                            return 'Yüzde';
                        } elseif ($record->drop_amount > 0) {
                            return 'Tutar';
                        }
                        return 'Yok';
                    })
                    ->colors([
                        'success' => 'Her İkisi',
                        'primary' => 'Yüzde',
                        'warning' => 'Tutar',
                        'danger' => 'Yok',
                    ]),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('product_id')
                    ->label('Ürün')
                    ->relationship('product', 'title'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
