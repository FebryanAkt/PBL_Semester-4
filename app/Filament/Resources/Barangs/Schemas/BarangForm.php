<?php

namespace App\Filament\Resources\Barangs\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class BarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Produk')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('category')
                ->label('Kategori')
                ->options([
                    'Elektronik' => 'Elektronik',
                    'Furniture'  => 'Furniture',
                    'Fashion'    => 'Fashion',
                    'Hobi'       => 'Hobi',
                ])
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(4),

            Forms\Components\FileUpload::make('image')
                ->label('Gambar')
                ->image()
                ->directory('barangs'),

            Forms\Components\TextInput::make('price')
                ->label('Harga')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'tersedia' => 'Tersedia',
                    'booking'  => 'Booking',
                    'terjual'  => 'Terjual',
                ])
                ->default('tersedia'),

            Forms\Components\TextInput::make('location')
                ->label('Lokasi')
                ->maxLength(100),
        ]);
    }
}