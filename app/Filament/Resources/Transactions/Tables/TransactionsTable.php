<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name'),
            Tables\Columns\TextColumn::make('item.name'),
            Tables\Columns\TextColumn::make('price'),

            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'success',
                    'danger' => 'cancel',
                ]),
        ]);
    }
}