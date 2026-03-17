<?php

namespace App\Filament\Resources\SupplierResource\RelationManagers;

use App\Enums\PaymentMethod;
use App\Enums\PurchaseUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'purchases';

    protected static ?string $title = 'Purchases';

    public static function getModelLabel(): string
    {
        return __('Purchase');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product')
                    ->label(__('Product'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $qty = (float) ($get('quantity') ?? 0);
                        $price = (float) ($get('unit_price') ?? 0);
                        $set('total', number_format($qty * $price, 2, '.', ''));
                    }),
                Forms\Components\Select::make('unit')
                    ->label(__('Unit'))
                    ->options(PurchaseUnit::options())
                    ->default('piece'),
                Forms\Components\TextInput::make('unit_price')
                    ->label(__('Unit Price'))
                    ->numeric()
                    ->step(0.01)
                    ->prefix('$')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $qty = (float) ($get('quantity') ?? 0);
                        $price = (float) ($get('unit_price') ?? 0);
                        $set('total', number_format($qty * $price, 2, '.', ''));
                    }),
                Forms\Components\TextInput::make('total')
                    ->label(__('Total'))
                    ->numeric()
                    ->prefix('$')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\DatePicker::make('date')
                    ->label(__('Date'))
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('payment_method')
                    ->label(__('Payment Method'))
                    ->options(PaymentMethod::options()),
                Forms\Components\Textarea::make('notes')
                    ->label(__('Notes'))
                    ->rows(2)
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('Date'))
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product')
                    ->label(__('Product'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Qty'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
