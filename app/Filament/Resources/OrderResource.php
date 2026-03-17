<?php

namespace App\Filament\Resources;

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Location;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Orders');
    }

    public static function getModelLabel(): string
    {
        return __('Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Orders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('Customer'))
                        ->icon('heroicon-o-user')
                        ->description(__('Customer information'))
                        ->schema(static::getCustomerFormSchema()),

                    Wizard\Step::make(__('Items'))
                        ->icon('heroicon-o-cube')
                        ->description(__('Order items'))
                        ->schema(static::getItemsFormSchema()),

                    Wizard\Step::make(__('Details'))
                        ->icon('heroicon-o-document-text')
                        ->description(__('Order details'))
                        ->schema(static::getDetailsFormSchema()),
                ])
                    ->columnSpanFull()
                    ->skippable(),
            ]);
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    public static function getCustomerFormSchema(): array
    {
        return [
            Forms\Components\Section::make(__('Customer'))
                ->schema([
                    Forms\Components\TextInput::make('customer_name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('customer_phone')
                        ->label(__('Phone'))
                        ->tel()
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $state, Set $set) {
                            $customer = Customer::where('phone', $state)->first();
                            if ($customer) {
                                $set('customer_name', $customer->name);
                                $set('customer_email', $customer->email);
                                $set('customer_id', $customer->id);
                            }
                        }),
                    Forms\Components\TextInput::make('customer_email')
                        ->label(__('Email'))
                        ->email()
                        ->maxLength(255),
                    Forms\Components\Hidden::make('customer_id'),
                ])->columns(3),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    public static function getItemsFormSchema(): array
    {
        return [
            Forms\Components\Repeater::make('lines')
                ->relationship()
                ->label(__('Order Lines'))
                ->schema([
                    Forms\Components\Select::make('item_id')
                        ->label(__('Item'))
                        ->options(fn () => Item::active()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($state) {
                                $item = Item::find($state);
                                if ($item) {
                                    $set('unit_price', $item->price ?? 0);
                                    $set('description', $item->name);
                                }
                            }
                        }),
                    Forms\Components\TextInput::make('description')
                        ->label(__('Description'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('variant')
                        ->label(__('Variant'))
                        ->maxLength(255),
                    Forms\Components\Select::make('egg_size')
                        ->label(__('Egg Size'))
                        ->options(\App\Enums\EggSize::options())
                        ->live()
                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                            if ($state) {
                                $size = \App\Enums\EggSize::tryFrom($state);
                                $unit = $get('unit_type') ? \App\Enums\UnitType::tryFrom($get('unit_type')) : null;
                                $price = app(\App\Services\PricingService::class)
                                    ->resolveLinePrice($size, $unit, null, currentTenant()?->id);
                                if ($price > 0) {
                                    $set('unit_price', number_format($price, 2, '.', ''));
                                    $qty = (int) ($get('quantity') ?? 0);
                                    $set('subtotal', number_format($qty * $price, 2, '.', ''));
                                }
                            }
                        }),
                    Forms\Components\Select::make('unit_type')
                        ->label(__('Unit'))
                        ->options(\App\Enums\UnitType::options()),
                    Forms\Components\TextInput::make('quantity')
                        ->label(__('Qty'))
                        ->numeric()
                        ->required()
                        ->default(1)
                        ->minValue(1)
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $qty = (int) ($get('quantity') ?? 0);
                            $price = (float) ($get('unit_price') ?? 0);
                            $set('subtotal', number_format($qty * $price, 2, '.', ''));
                        }),
                    Forms\Components\TextInput::make('unit_price')
                        ->label(__('Unit Price'))
                        ->numeric()
                        ->step(0.01)
                        ->prefix('$')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $qty = (int) ($get('quantity') ?? 0);
                            $price = (float) ($get('unit_price') ?? 0);
                            $set('subtotal', number_format($qty * $price, 2, '.', ''));
                        }),
                    Forms\Components\TextInput::make('subtotal')
                        ->label(__('Subtotal'))
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated(),
                ])
                ->columns(8)
                ->defaultItems(1)
                ->addActionLabel(__('Add Line'))
                ->reorderable(false)
                ->collapsible()
                ->itemLabel(fn (array $state): string => ($state['description'] ?? 'Item') . ' x' . ($state['quantity'] ?? 1)),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\Section::make(__('Order Details'))
                ->schema([
                    Forms\Components\Select::make('location_id')
                        ->label(__('Location'))
                        ->options(fn () => Location::active()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->visible(fn () => hasModule('locations')),
                    Forms\Components\Select::make('priority')
                        ->label(__('Priority'))
                        ->options(OrderPriority::options())
                        ->default(OrderPriority::Normal->value),
                    Forms\Components\DatePicker::make('estimated_at')
                        ->label(__('Estimated Completion')),
                    Forms\Components\TextInput::make('initial_payment')
                        ->label(__('Initial Payment'))
                        ->numeric()
                        ->step(0.01)
                        ->prefix('$')
                        ->default(0),
                ])->columns(2),

            Forms\Components\Section::make(__('Delivery'))
                ->schema([
                    Forms\Components\Select::make('delivery_type')
                        ->label(__('Delivery Type'))
                        ->options(\App\Enums\DeliveryType::options())
                        ->live(),
                    Forms\Components\DatePicker::make('delivery_date')
                        ->label(__('Delivery Date')),
                    Forms\Components\TextInput::make('delivery_time')
                        ->label(__('Delivery Time'))
                        ->placeholder('e.g., 8:00-10:00 AM')
                        ->visible(fn (Get $get): bool => $get('delivery_type') === 'delivery'),
                    Forms\Components\Textarea::make('delivery_notes')
                        ->label(__('Delivery Notes'))
                        ->rows(2)
                        ->visible(fn (Get $get): bool => $get('delivery_type') === 'delivery')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('shipping_cost')
                        ->label(__('Shipping Cost'))
                        ->numeric()
                        ->step(0.01)
                        ->prefix('$')
                        ->default(0)
                        ->visible(fn (Get $get): bool => $get('delivery_type') === 'delivery'),
                ])->columns(2),

            Forms\Components\Section::make(__('Notes & Attachments'))
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label(__('Notes'))
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('attachments')
                        ->label(__('Attachments'))
                        ->multiple()
                        ->disk('public')
                        ->directory('order-attachments')
                        ->maxFiles(5)
                        ->columnSpanFull(),
                ])->collapsible(),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    public static function getWizardSteps(): array
    {
        return [
            Wizard\Step::make(__('Customer'))
                ->icon('heroicon-o-user')
                ->description(__('Customer information'))
                ->schema(static::getCustomerFormSchema()),

            Wizard\Step::make(__('Items'))
                ->icon('heroicon-o-cube')
                ->description(__('Order items'))
                ->schema(static::getItemsFormSchema()),

            Wizard\Step::make(__('Details'))
                ->icon('heroicon-o-document-text')
                ->description(__('Order details'))
                ->schema(static::getDetailsFormSchema()),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->color())
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('Priority'))
                    ->badge()
                    ->color(fn (OrderPriority $state): string => $state->color())
                    ->formatStateUsing(fn (OrderPriority $state): string => $state->label())
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location.name')
                    ->label(__('Location'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn () => hasModule('locations')),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label(__('Balance'))
                    ->money()
                    ->color(fn (Order $record): string => $record->balance > 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(OrderStatus::options()),
                Tables\Filters\SelectFilter::make('priority')
                    ->label(__('Priority'))
                    ->options(OrderPriority::options()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('advance_status')
                    ->label(fn (Order $record): string => match ($record->status) {
                        OrderStatus::Draft => __('Submit'),
                        OrderStatus::Pending => __('Confirm'),
                        OrderStatus::Confirmed => __('Start'),
                        OrderStatus::InProgress => __('Complete'),
                        default => __('Action'),
                    })
                    ->icon('heroicon-o-arrow-right')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool => ! in_array($record->status, [
                        OrderStatus::Completed,
                        OrderStatus::Cancelled,
                    ]))
                    ->action(function (Order $record) {
                        match ($record->status) {
                            OrderStatus::Draft, OrderStatus::Pending => $record->confirm(),
                            OrderStatus::Confirmed, OrderStatus::InProgress => $record->complete(),
                            default => null,
                        };
                    }),
                Action::make('cancel')
                    ->label(__('Cancel'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool => ! in_array($record->status, [
                        OrderStatus::Completed,
                        OrderStatus::Cancelled,
                    ]))
                    ->action(fn (Order $record) => $record->cancel()),
                Action::make('duplicate')
                    ->label(__('Duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (Order $record) {
                        $newOrder = $record->duplicate();

                        Notification::make()
                            ->title(__('Order duplicated'))
                            ->body(__('New order #:id created as draft.', ['id' => $newOrder->id]))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return array_filter([
            hasModule('payments') ? RelationManagers\PaymentsRelationManager::class : null,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
