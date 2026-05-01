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
                ->searchable()
                ->required(),

            Select::make('item_id')
                ->relationship('item', 'name')
                ->searchable()
                ->required(),

            TextInput::make('price')
                ->numeric()
                ->required(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'success' => 'Success',
                    'cancel' => 'Cancel',
                ])
                ->required(),
        ]);
    }
}