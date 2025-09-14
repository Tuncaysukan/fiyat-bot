<?php

namespace App\Filament\Resources\Products\Tables;

use App\Jobs\ScanProductPrice;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('marketplace.name')
                    ->label('Marketplace')
                    ->badge()
                    ->sortable(),

                TextColumn::make('last_price')
                    ->label('Son Fiyat')
                    ->money('TRY')
                    ->sortable(),

                TextColumn::make('price_change_percent')
                    ->label('Değişim')
                    ->formatStateUsing(function ($state) {
                        if ($state > 0) {
                            return '📈 +' . number_format($state, 2) . '%';
                        } elseif ($state < 0) {
                            return '📉 ' . number_format($state, 2) . '%';
                        }
                        return '➡️ ' . number_format($state, 2) . '%';
                    })
                    ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray')),

                BadgeColumn::make('parse_strategy')
                    ->label('Strateji')
                    ->colors([
                        'primary' => 'selector',
                        'success' => 'ldjson',
                        'warning' => 'api',
                        'danger' => 'custom',
                    ]),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('marketplace_id')
                    ->label('Marketplace')
                    ->relationship('marketplace', 'name'),

                SelectFilter::make('parse_strategy')
                    ->label('Parse Stratejisi')
                    ->options([
                        'selector' => 'CSS Selector',
                        'ldjson' => 'JSON-LD',
                        'api' => 'API',
                        'custom' => 'Custom',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Aktif Durumu'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('scan_now')
                    ->label('Şimdi Tara')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function ($record) {
                        ScanProductPrice::dispatch($record->id);
                    })
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
