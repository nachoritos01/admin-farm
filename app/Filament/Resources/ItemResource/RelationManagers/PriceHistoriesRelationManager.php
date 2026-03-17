<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use App\Models\PriceHistory;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PriceHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'priceHistories';

    protected static ?string $title = 'Price History';

    protected static ?string $modelLabel = 'Price Change';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('effective_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('effective_date')
                    ->label('Date')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('previous_price')
                    ->label('Previous')
                    ->money()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->label('New Price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('change_percentage')
                    ->label('Change')
                    ->suffix('%')
                    ->color(fn (PriceHistory $record): string => match (true) {
                        $record->change_percentage === null => 'gray',
                        $record->change_percentage > 0 => 'success',
                        $record->change_percentage < 0 => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('changedByUser.name')
                    ->label('Changed By')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(40)
                    ->toggleable(),
            ]);
    }
}
