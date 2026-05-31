<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255),

                TextInput::make('description')
                    ->label('Deskripsi')
                    ->maxLength(1000),

                TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->required(),
            ]);
    }
}
