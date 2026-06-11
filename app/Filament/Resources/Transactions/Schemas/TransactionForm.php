<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->label('Nama Pengguna')
                ->searchable()
                ->required(),

            Select::make('item_id')
                ->relationship('item', 'name')
                ->label('Nama Barang')
                ->searchable()
                ->required(),

            TextInput::make('price')
                ->label('Harga')
                ->numeric()
                ->required(),

            Select::make('status')
                ->options([
                    'booking' => 'Pesanan',
                    'tersedia' => 'Tersedia',
                    'terjual' => 'Terjual',
                ])
                ->required(),
        ]);
    }
}