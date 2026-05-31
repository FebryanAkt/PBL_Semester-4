<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->label('Nama'),   
            Tables\Columns\TextColumn::make('item.name')
                ->label('Barang'),
            Tables\Columns\TextColumn::make('harga')->money('idr', true),

            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'berhasil',
                    'danger' => 'batal',
                ]),
        ]);
    }
}