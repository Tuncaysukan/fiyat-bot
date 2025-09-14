<?php

namespace App\Filament\Resources\Subscribers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Abone Adı')
                    ->maxLength(255)
                    ->placeholder('Örn: Ahmet Yılmaz')
                    ->helperText('Abonenin adı (opsiyonel)')
                    ->columnSpan(1),

                TextInput::make('chat_id')
                    ->label('Telegram Chat ID')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('123456789')
                    ->helperText('Telegram kullanıcısının benzersiz chat ID\'si')
                    ->columnSpan(1),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Bu abone fiyat düşüş bildirimlerini alacak mı?')
                    ->columnSpanFull(),
            ]);
    }
}
