<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
            ->label('Nama')    
            ->searchable(),

            Tables\Columns\TextColumn::make('slug'),
        ]);
    }
}