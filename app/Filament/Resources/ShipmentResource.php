<?php

namespace App\Filament\Resources;

use App\Enums\ShipmentStatus;
use App\Filament\Resources\ShipmentResource\Pages;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 15;

    public static function getNavigationLabel(): string
    {
        return __('Shipments');
    }

    public static function getModelLabel(): string
    {
        return __('Shipment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Shipments');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function canAccess(): bool
    {
        return hasModule('shipments');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('shipments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Shipment Details'))
                    ->schema([
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->label(__('Scheduled Date'))
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('driver_id')
                            ->label(__('Driver'))
                            ->options(function () {
                                $tenant = currentTenant();

                                return $tenant
                                    ? $tenant->users()->pluck('users.name', 'users.id')
                                    : [];
                            })
                            ->searchable(),
                        Forms\Components\TextInput::make('zone')
                            ->label(__('Zone'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('vehicle')
                            ->label(__('Vehicle'))
                            ->maxLength(255)
                            ->placeholder('e.g., Ford F-150 #3'),
                        Forms\Components\Textarea::make('route')
                            ->label(__('Route'))
                            ->rows(2)
                            ->placeholder('Describe the delivery route'),
                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options(ShipmentStatus::options())
                            ->default('scheduled')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make(__('Notes'))
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label(__('Notes'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('scheduled_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label(__('Date'))
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver.name')
                    ->label(__('Driver'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone')
                    ->label(__('Zone'))
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('vehicle')
                    ->label(__('Vehicle'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (ShipmentStatus $state): string => $state->color())
                    ->formatStateUsing(fn (ShipmentStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label(__('Orders'))
                    ->counts('orders')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(ShipmentStatus::options()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_in_transit')
                    ->label(__('In Transit'))
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Shipment $record): bool => $record->status === ShipmentStatus::Scheduled)
                    ->action(fn (Shipment $record) => $record->update(['status' => ShipmentStatus::InTransit])),
                Tables\Actions\Action::make('mark_delivered')
                    ->label(__('Delivered'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Shipment $record): bool => $record->status === ShipmentStatus::InTransit)
                    ->action(fn (Shipment $record) => $record->update(['status' => ShipmentStatus::Delivered])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}
