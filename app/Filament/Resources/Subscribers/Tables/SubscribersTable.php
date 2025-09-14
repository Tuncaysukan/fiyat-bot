<?php

namespace App\Filament\Resources\Subscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;

class SubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Abone Adı')
                    ->searchable()
                    ->sortable()
                    ->placeholder('İsimsiz'),

                TextColumn::make('chat_id')
                    ->label('Chat ID')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Chat ID yok'),

                TextColumn::make('bot_name')
                    ->label('Bot Adı')
                    ->searchable()
                    ->placeholder('Bot adı yok')
                    ->badge()
                    ->color('info'),

                TextColumn::make('bot_token')
                    ->label('Bot Token')
                    ->limit(20)
                    ->placeholder('Token yok')
                    ->copyable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Son Güncelleme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktif Durumu'),
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
