<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingActions extends BaseWidget
{
    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('farm.pending_actions');
    }

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->whereIn('status', [
                        OrderStatus::Draft,
                        OrderStatus::Pending,
                        OrderStatus::Confirmed,
                        OrderStatus::InProgress,
                    ])
                    ->orderByRaw("
                        CASE status
                            WHEN 'draft' THEN 1
                            WHEN 'pending' THEN 2
                            WHEN 'confirmed' THEN 3
                            WHEN 'in_progress' THEN 4
                            ELSE 5
                        END
                    ")
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label(__('Customer')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->color())
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->since(),
            ])
            ->actions([
                Action::make('next_step')
                    ->label(fn (Order $record): string => match ($record->status) {
                        OrderStatus::Draft => __('Confirm'),
                        OrderStatus::Pending => __('Confirm'),
                        OrderStatus::Confirmed => __('Start Processing'),
                        OrderStatus::InProgress => __('Complete'),
                        default => __('Actions'),
                    })
                    ->icon(fn (Order $record): string => match ($record->status) {
                        OrderStatus::Draft, OrderStatus::Pending => 'heroicon-o-check',
                        OrderStatus::Confirmed => 'heroicon-o-play',
                        OrderStatus::InProgress => 'heroicon-o-check-circle',
                        default => 'heroicon-o-arrow-right',
                    })
                    ->color(fn (Order $record): string => match ($record->status) {
                        OrderStatus::Draft, OrderStatus::Pending => 'info',
                        OrderStatus::Confirmed => 'primary',
                        OrderStatus::InProgress => 'success',
                        default => 'gray',
                    })
                    ->size('sm')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        match ($record->status) {
                            OrderStatus::Draft, OrderStatus::Pending => $record->confirm(),
                            OrderStatus::Confirmed, OrderStatus::InProgress => $record->complete(),
                            default => null,
                        };
                    }),
            ])
            ->paginated(false);
    }
}
