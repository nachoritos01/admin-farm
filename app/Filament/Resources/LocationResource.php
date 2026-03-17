<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('Locations');
    }

    public static function getModelLabel(): string
    {
        return __('Location');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Locations');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public static function canAccess(): bool
    {
        return hasModule('locations');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('locations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'store' => __('Store'),
                                'warehouse' => __('Warehouse'),
                                'office' => __('Office'),
                                'other' => __('Other'),
                            ])
                            ->default('store'),
                        Forms\Components\Textarea::make('address')
                            ->label(__('Address'))
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->label(__('City'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->label(__('State / Province'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip')
                            ->label(__('Postal Code'))
                            ->maxLength(20),
                        Forms\Components\TextInput::make('country')
                            ->label(__('Country'))
                            ->maxLength(100),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('Phone'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('schedule')
                            ->label(__('Schedule'))
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make(__('Coordinates'))
                    ->schema([
                        Forms\Components\TextInput::make('lat')
                            ->label(__('Latitude'))
                            ->numeric()
                            ->step(0.0000001),
                        Forms\Components\TextInput::make('lng')
                            ->label(__('Longitude'))
                            ->numeric()
                            ->step(0.0000001),
                        Forms\Components\TextInput::make('maps_url')
                            ->label(__('Google Maps URL'))
                            ->url()
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make(__('Metadata'))
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->label(__('Metadata'))
                            ->columnSpanFull(),
                    ])->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make(__('Status'))
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->label(__('Country'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->copyable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
            ])
            ->filters([
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
