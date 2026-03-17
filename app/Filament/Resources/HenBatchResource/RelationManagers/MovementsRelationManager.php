<?php

namespace App\Filament\Resources\HenBatchResource\RelationManagers;

use App\Enums\HenMovementType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'movements';

    protected static ?string $title = 'Movements';

    public static function getModelLabel(): string
    {
        return __('Movement');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label(__('Type'))
                    ->options(HenMovementType::options())
                    ->required()
                    ->live(),
                Forms\Components\Select::make('death_cause')
                    ->label(__('Death Cause'))
                    ->options(\App\Enums\DeathCause::options())
                    ->visible(fn (Forms\Get $get): bool => $get('type') === 'death'),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Forms\Components\DatePicker::make('date')
                    ->label(__('Date'))
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('reason')
                    ->label(__('Reason'))
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (HenMovementType $state): string => $state->color())
                    ->formatStateUsing(fn (HenMovementType $state): string => $state->label()),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Qty'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('Date'))
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label(__('Reason'))
                    ->limit(40)
                    ->toggleable(),
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
