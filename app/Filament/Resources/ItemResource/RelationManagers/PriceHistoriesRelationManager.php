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

    public static function getModelLabel(): string
    {
        return __('Price Change');
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('effective_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('effective_date')
                    ->label(__('Date'))
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('previous_price')
                    ->label(__('Previous'))
                    ->money()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('New Price'))
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('change_percentage')
                    ->label(__('Change'))
                    ->suffix('%')
                    ->color(fn (PriceHistory $record): string => match (true) {
                        $record->change_percentage === null => 'gray',
                        $record->change_percentage > 0 => 'success',
                        $record->change_percentage < 0 => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('changedByUser.name')
                    ->label(__('Changed By'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label(__('Reason'))
                    ->limit(40)
                    ->toggleable(),
            ]);
    }
}
