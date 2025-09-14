<?php

namespace App\Filament\Widgets;

use App\Models\PriceHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPriceDropsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Son Fiyat Değişimleri';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PriceHistory::with(['product.marketplace'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Ürün')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('product.marketplace.name')
                    ->label('Marketplace')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_change')
                    ->label('Değişim')
                    ->formatStateUsing(function ($record) {
                        $previous = $record->product->history()
                            ->where('created_at', '<', $record->created_at)
                            ->latest()
                            ->first();

                        if (!$previous) {
                            return 'İlk Kayıt';
                        }

                        $change = $record->price - $previous->price;
                        $percent = $previous->price > 0 ? ($change / $previous->price) * 100 : 0;

                        if ($change > 0) {
                            return '📈 +₺' . number_format($change, 2) . ' (+' . number_format($percent, 2) . '%)';
                        } elseif ($change < 0) {
                            return '📉 -₺' . number_format(abs($change), 2) . ' (' . number_format($percent, 2) . '%)';
                        }
                        return '➡️ Değişim yok';
                    })
                    ->color(function ($record) {
                        $previous = $record->product->history()
                            ->where('created_at', '<', $record->created_at)
                            ->latest()
                            ->first();

                        if (!$previous) return 'gray';

                        $change = $record->price - $previous->price;
                        return $change > 0 ? 'success' : ($change < 0 ? 'danger' : 'gray');
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
