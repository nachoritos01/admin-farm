<?php

namespace App\Filament\Resources;

use App\Enums\SupplierCategory;
use App\Enums\SupplierStatus;
use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 40;

    public static function getNavigationLabel(): string
    {
        return __('Suppliers');
    }

    public static function getModelLabel(): string
    {
        return __('Supplier');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Suppliers');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Farm');
    }

    public static function canAccess(): bool
    {
        return hasModule('suppliers');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('suppliers');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Supplier Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Company Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_name')
                            ->label(__('Contact Name'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('Phone'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->label(__('Category'))
                            ->options(SupplierCategory::options())
                            ->default('other')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options(SupplierStatus::options())
                            ->default('active')
                            ->required(),
                        Forms\Components\Textarea::make('address')
                            ->label(__('Address'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('Products & Rating'))
                    ->schema([
                        Forms\Components\TagsInput::make('products')
                            ->label(__('Products'))
                            ->placeholder('Add a product'),
                        Forms\Components\Select::make('rating')
                            ->label(__('Rating'))
                            ->options([
                                1 => __('1 - Poor'),
                                2 => __('2 - Fair'),
                                3 => __('3 - Good'),
                                4 => __('4 - Very Good'),
                                5 => __('5 - Excellent'),
                            ]),
                    ])->columns(2)
                    ->collapsible(),

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
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label(__('Contact'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('Category'))
                    ->badge()
                    ->color(fn (SupplierCategory $state): string => $state->color())
                    ->formatStateUsing(fn (SupplierCategory $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (SupplierStatus $state): string => $state->color())
                    ->formatStateUsing(fn (SupplierStatus $state): string => $state->label()),
                Tables\Columns\TextColumn::make('rating')
                    ->label(__('Rating'))
                    ->formatStateUsing(fn (?int $state): string => $state ? str_repeat('*', $state) : '-')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expenses_count')
                    ->label(__('Expenses'))
                    ->counts('expenses')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('Category'))
                    ->options(SupplierCategory::options()),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(SupplierStatus::options()),
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
            RelationManagers\PurchasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
