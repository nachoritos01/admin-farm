<?php

namespace App\Filament\Resources;

use App\Enums\EggSize;
use App\Enums\QualityGrade;
use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Items');
    }

    public static function getModelLabel(): string
    {
        return __('Item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Items');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Basic Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sku')
                            ->label(__('SKU'))
                            ->maxLength(100),
                        Forms\Components\TextInput::make('category')
                            ->label(__('Category'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->label(__('Price'))
                            ->numeric()
                            ->step(0.01)
                            ->prefix('$'),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0),
                    ])->columns(2),

                Forms\Components\Section::make(__('Egg Product Details'))
                    ->schema([
                        Forms\Components\TextInput::make('unit')
                            ->label(__('Unit'))
                            ->maxLength(50)
                            ->placeholder('e.g., carton, dozen, piece'),
                        Forms\Components\Select::make('egg_size')
                            ->label(__('Egg Size'))
                            ->options(EggSize::options()),
                        Forms\Components\Select::make('egg_quality')
                            ->label(__('Egg Quality'))
                            ->options(QualityGrade::options()),
                        Forms\Components\TextInput::make('wholesale_price')
                            ->label(__('Wholesale Price'))
                            ->numeric()
                            ->step(0.01)
                            ->prefix('$'),
                    ])->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make(__('Description'))
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('Variants'))
                    ->schema([
                        Forms\Components\KeyValue::make('variants')
                            ->label(__('Variants'))
                            ->keyLabel(__('Option'))
                            ->valueLabel(__('Value'))
                            ->columnSpanFull(),
                    ])->collapsible(),

                Forms\Components\Section::make(__('Images'))
                    ->schema([
                        Forms\Components\FileUpload::make('photos')
                            ->label(__('Photos'))
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('items')
                            ->reorderable()
                            ->maxFiles(5)
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])->collapsible(),

                Forms\Components\Section::make(__('Tags'))
                    ->schema([
                        Forms\Components\TagsInput::make('tags')
                            ->label(__('Tags'))
                            ->separator(',')
                            ->columnSpanFull(),
                    ])->collapsible(),

                Forms\Components\Section::make(__('Metadata'))
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->label(__('Metadata'))
                            ->columnSpanFull(),
                    ])->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_photo')
                    ->label(__('Photo'))
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('Category'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wholesale_price')
                    ->label(__('Wholesale'))
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('egg_size')
                    ->label(__('Size'))
                    ->badge()
                    ->color(fn (?EggSize $state): string => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn (?EggSize $state): string => $state?->label() ?? '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (Item $record): string => $record->is_active ? __('Deactivate') : __('Activate'))
                    ->icon(fn (Item $record): string => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (Item $record): string => $record->is_active ? 'warning' : 'success')
                    ->action(fn (Item $record) => $record->update(['is_active' => ! $record->is_active])),
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
            RelationManagers\PriceHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
