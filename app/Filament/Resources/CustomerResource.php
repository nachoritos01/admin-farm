<?php

namespace App\Filament\Resources;

use App\Enums\CustomerType;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('Customers');
    }

    public static function getModelLabel(): string
    {
        return __('Customer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Customers');
    }

    public static function canAccess(): bool
    {
        return hasModule('customer_portal');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('customer_portal');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Customer Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('Phone'))
                            ->tel()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                        Forms\Components\Select::make('customer_type')
                            ->label(__('Customer Type'))
                            ->options(CustomerType::options())
                            ->default('retail')
                            ->required(),
                        Forms\Components\TextInput::make('zone')
                            ->label(__('Zone'))
                            ->maxLength(255)
                            ->placeholder('e.g., North, Downtown'),
                        Forms\Components\TextInput::make('preferred_price')
                            ->label(__('Preferred Price'))
                            ->numeric()
                            ->step(0.01)
                            ->prefix('$')
                            ->placeholder('Negotiated price per unit'),
                    ])->columns(2),

                Forms\Components\Section::make(__('Addresses'))
                    ->schema([
                        Forms\Components\Repeater::make('addresses')
                            ->relationship()
                            ->label('')
                            ->schema([
                                Forms\Components\Select::make('label')
                                    ->label(__('Label'))
                                    ->options([
                                        'home' => __('Home'),
                                        'office' => __('Office'),
                                        'other' => __('Other'),
                                    ])
                                    ->default('home'),
                                Forms\Components\TextInput::make('street')
                                    ->label(__('Street'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('district')
                                    ->label(__('District'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('city')
                                    ->label(__('City'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('state')
                                    ->label(__('State / Province'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('zip')
                                    ->label(__('Postal Code'))
                                    ->maxLength(20),
                                Forms\Components\Textarea::make('references')
                                    ->label(__('References'))
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_default')
                                    ->label(__('Default')),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel(__('Add address'))
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): string => match ($state['label'] ?? 'home') {
                                'home' => __('Home'),
                                'office' => __('Office'),
                                default => __('Other'),
                            } . ($state['street'] ? ' - ' . $state['street'] : '')),
                    ])->collapsible(),

                Forms\Components\Section::make(__('Portal Access'))
                    ->schema([
                        Forms\Components\Toggle::make('portal_enabled')
                            ->label(__('Enable Portal'))
                            ->helperText(__('The customer will use their phone + password to access.'))
                            ->live()
                            ->afterStateHydrated(fn (Forms\Components\Toggle $component, ?Customer $record) => $component->state(filled($record?->password)))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('portal_enabled'))
                            ->required(fn (Forms\Get $get, string $operation, ?Customer $record): bool => (bool) $get('portal_enabled') && ($operation === 'create' || ! filled($record?->password)))
                            ->maxLength(255)
                            ->helperText(fn (string $operation): string => $operation === 'edit' ? __('Leave empty to keep current password.') : ''),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label(__('Confirm Password'))
                            ->password()
                            ->revealable()
                            ->visible(fn (Forms\Get $get): bool => (bool) $get('portal_enabled'))
                            ->dehydrated(false),
                    ])->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make(__('Loyalty'))
                    ->schema([
                        Forms\Components\Placeholder::make('loyalty_points_display')
                            ->label(__('Available Points'))
                            ->content(fn (?Customer $record): string => $record ? number_format($record->loyalty_points) : '0'),
                        Forms\Components\Placeholder::make('loyalty_lifetime_display')
                            ->label(__('Lifetime Points'))
                            ->content(fn (?Customer $record): string => $record ? number_format($record->loyalty_lifetime_points) : '0'),
                        Forms\Components\Placeholder::make('loyalty_tier_display')
                            ->label(__('Current Tier'))
                            ->content(fn (?Customer $record): string => $record ? $record->loyalty_tier->label() . ' (x' . $record->loyalty_tier->multiplier() . ')' : 'Bronze'),
                        Forms\Components\Toggle::make('first_purchase_bonus')
                            ->label(__('First Purchase Bonus Credited'))
                            ->helperText(__('Whether the first purchase bonus has been credited.')),
                    ])->columns(4)
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn (): bool => hasModule('loyalty')),

                Forms\Components\Section::make(__('Tags & Metadata'))
                    ->schema([
                        Forms\Components\TagsInput::make('tags')
                            ->label(__('Tags'))
                            ->separator(','),
                        Forms\Components\KeyValue::make('metadata')
                            ->label(__('Metadata')),
                        Forms\Components\DatePicker::make('birthday')
                            ->label(__('Birthday')),
                    ])->columns(3)
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make(__('Notes'))
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label(__('Notes'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('password')
                    ->label(__('Portal'))
                    ->boolean()
                    ->getStateUsing(fn (Customer $record): bool => filled($record->password))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('customer_type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (CustomerType $state): string => $state->color())
                    ->formatStateUsing(fn (CustomerType $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone')
                    ->label(__('Zone'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label(__('Orders'))
                    ->counts('orders')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Registered'))
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_type')
                    ->label(__('Type'))
                    ->options(CustomerType::options()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
