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

    protected static ?string $navigationLabel = 'Shipments';

    protected static ?string $modelLabel = 'Shipment';

    protected static ?string $pluralModelLabel = 'Shipments';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 15;

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
                Forms\Components\Section::make('Shipment Details')
                    ->schema([
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->label('Scheduled Date')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('driver_id')
                            ->label('Driver')
                            ->options(function () {
                                $tenant = currentTenant();

                                return $tenant
                                    ? $tenant->users()->pluck('users.name', 'users.id')
                                    : [];
                            })
                            ->searchable(),
                        Forms\Components\TextInput::make('zone')
                            ->label('Zone')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(ShipmentStatus::options())
                            ->default('scheduled')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
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
                    ->label('Date')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone')
                    ->label('Zone')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ShipmentStatus $state): string => $state->color())
                    ->formatStateUsing(fn (ShipmentStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(ShipmentStatus::options()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_in_transit')
                    ->label('In Transit')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Shipment $record): bool => $record->status === ShipmentStatus::Scheduled)
                    ->action(fn (Shipment $record) => $record->update(['status' => ShipmentStatus::InTransit])),
                Tables\Actions\Action::make('mark_delivered')
                    ->label('Delivered')
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
