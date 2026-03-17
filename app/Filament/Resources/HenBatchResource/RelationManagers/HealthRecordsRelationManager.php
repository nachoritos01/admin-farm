<?php

namespace App\Filament\Resources\HenBatchResource\RelationManagers;

use App\Enums\HealthRecordType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HealthRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'healthRecords';

    protected static ?string $title = 'Health Records';

    public static function getModelLabel(): string
    {
        return __('Health Record');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label(__('Type'))
                    ->options(HealthRecordType::options())
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->label(__('Date'))
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('medication')
                    ->label(__('Medication'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('dosage')
                    ->label(__('Dosage'))
                    ->maxLength(255),
                Forms\Components\DatePicker::make('next_due_date')
                    ->label(__('Next Due Date')),
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
                    ->color(fn (HealthRecordType $state): string => $state->color())
                    ->formatStateUsing(fn (HealthRecordType $state): string => $state->label()),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('Date'))
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('medication')
                    ->label(__('Medication'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('dosage')
                    ->label(__('Dosage'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('next_due_date')
                    ->label(__('Next Due'))
                    ->date('Y-m-d')
                    ->color(fn ($state): string => $state && $state < now() ? 'danger' : 'success')
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
