<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('farm.latest_orders');
    }

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label(__('Customer'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label(__('Phone'))
                    ->copyable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->color())
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->since(),
            ])
            ->paginated(false);
    }
}
